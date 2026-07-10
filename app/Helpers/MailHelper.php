<?php
declare(strict_types=1);

/**
 * SMTP helper for sending transactional emails using app settings.
 */
class MailHelper
{
    /**
     * @return array<string, string>
     */
    public static function loadSettingsFromDatabase(): array
    {
        $defaults = [
            'email_notifications_enabled' => '1',
            'mail_host' => 'smtp.yourserver.com',
            'mail_port' => '587',
            'mail_username' => 'your-email@yourdomain.com',
            'mail_password' => 'your-password',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'no-reply@yourdomain.com',
            'mail_from_name' => APP_NAME,
            'support_email' => '',
            'recaptcha_enabled' => '0',
            'recaptcha_site_key' => '',
            'recaptcha_secret_key' => '',
        ];

        try {
            $db = Database::getInstance();
            foreach ($db->query('SELECT setting_key, setting_value FROM settings') as $row) {
                $key = (string) ($row['setting_key'] ?? '');
                if ($key === '') {
                    continue;
                }
                $defaults[$key] = (string) ($row['setting_value'] ?? '');
            }
        } catch (Throwable $exception) {
            // Keep defaults when settings table is not available.
        }

        return $defaults;
    }

    /**
     * @param array<string, string> $settings
     * @return array{success: bool, message: string}
     */
    public static function send(string $toEmail, string $subject, string $body, array $settings = [], bool $isHtml = false): array
    {
        $mailSettings = $settings !== [] ? $settings : self::loadSettingsFromDatabase();
        $isTestEmail = stripos($subject, 'SMTP settings test - ') === 0;

        $notificationsEnabled = (string) ($mailSettings['email_notifications_enabled'] ?? '1');
        if (!$isTestEmail && $notificationsEnabled !== '1') {
            $result = [
                'success' => false,
                'message' => 'Email notifications are disabled from Settings.',
            ];
            self::logMailFailure(trim($toEmail), $subject, $result['message']);
            return $result;
        }

        $host = trim((string) ($mailSettings['mail_host'] ?? ''));
        $port = (int) (($mailSettings['mail_port'] ?? '587') ?: 587);
        $encryption = strtolower(trim((string) ($mailSettings['mail_encryption'] ?? 'tls')));
        if ($encryption === 'starttls') {
            $encryption = 'tls';
        }
        $username = trim((string) ($mailSettings['mail_username'] ?? ''));
        $password = (string) ($mailSettings['mail_password'] ?? '');
        $fromAddress = trim((string) ($mailSettings['mail_from_address'] ?? ''));
        $fromName = trim((string) ($mailSettings['mail_from_name'] ?? APP_NAME));
        $recipient = trim($toEmail);

        $encodedSubject = self::encodeHeader($subject);
        $encodedFromName = self::encodeHeader($fromName);

        if ($host === '' || $port <= 0 || $fromAddress === '' || $recipient === '') {
            $result = [
                'success' => false,
                'message' => 'Missing required SMTP fields (host, port, from address, recipient).',
            ];
            self::logMailFailure($recipient, $subject, $result['message']);
            return $result;
        }

        if (!in_array($encryption, ['tls', 'ssl', 'none', ''], true)) {
            $result = [
                'success' => false,
                'message' => 'Invalid SMTP encryption mode. Use tls, ssl, or none.',
            ];
            self::logMailFailure($recipient, $subject, $result['message']);
            return $result;
        }

        $transportHost = $encryption === 'ssl' ? 'ssl://' . $host : $host;
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ]);

        $errno = 0;
        $errstr = '';
        $socket = @stream_socket_client($transportHost . ':' . $port, $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $context);
        if ($socket === false) {
            $result = [
                'success' => false,
                'message' => 'SMTP connection failed: ' . $errstr . ' (' . $errno . ')',
            ];
            self::logMailFailure($recipient, $subject, $result['message']);
            return $result;
        }

        stream_set_timeout($socket, 10);

        try {
            self::expectSmtpCode($socket, [220], 'SMTP greeting');

            self::sendSmtpCommand($socket, 'EHLO localhost');
            self::expectSmtpCode($socket, [250], 'EHLO');

            if ($encryption === 'tls') {
                self::sendSmtpCommand($socket, 'STARTTLS');
                self::expectSmtpCode($socket, [220], 'STARTTLS');

                $cryptoEnabled = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                if ($cryptoEnabled !== true) {
                    throw new RuntimeException('TLS handshake failed.');
                }

                self::sendSmtpCommand($socket, 'EHLO localhost');
                self::expectSmtpCode($socket, [250], 'EHLO after STARTTLS');
            }

            if ($username !== '') {
                self::sendSmtpCommand($socket, 'AUTH LOGIN');
                self::expectSmtpCode($socket, [334], 'AUTH LOGIN');

                self::sendSmtpCommand($socket, base64_encode($username));
                self::expectSmtpCode($socket, [334], 'SMTP username');

                self::sendSmtpCommand($socket, base64_encode($password));
                self::expectSmtpCode($socket, [235], 'SMTP password');
            }

            self::sendSmtpCommand($socket, 'MAIL FROM:<' . $fromAddress . '>');
            self::expectSmtpCode($socket, [250], 'MAIL FROM');

            self::sendSmtpCommand($socket, 'RCPT TO:<' . $recipient . '>');
            self::expectSmtpCode($socket, [250, 251], 'RCPT TO');

            self::sendSmtpCommand($socket, 'DATA');
            self::expectSmtpCode($socket, [354], 'DATA');

            $normalizedBody = str_replace(["\r\n", "\r"], "\n", $body);
            $normalizedBody = str_replace("\n", "\r\n", $normalizedBody);
            $safeBody = preg_replace('/^\./m', '..', $normalizedBody) ?? $normalizedBody;
            $message = '';
            $message .= 'From: ' . $encodedFromName . ' <' . $fromAddress . ">\r\n";
            $message .= 'To: <' . $recipient . ">\r\n";
            $message .= 'Subject: ' . $encodedSubject . "\r\n";
            $message .= 'Date: ' . gmdate('D, d M Y H:i:s O') . "\r\n";
            $message .= 'Message-ID: <' . bin2hex(random_bytes(8)) . '@' . preg_replace('/[^A-Za-z0-9.-]/', '', ($host !== '' ? $host : 'localhost')) . ">\r\n";
            $message .= "MIME-Version: 1.0\r\n";
            $message .= 'Content-Type: ' . ($isHtml ? 'text/html' : 'text/plain') . "; charset=UTF-8\r\n";
            $message .= "Content-Transfer-Encoding: 8bit\r\n";
            $message .= "\r\n";
            $message .= $safeBody . "\r\n.\r\n";

            fwrite($socket, $message);
            self::expectSmtpCode($socket, [250], 'Message delivery');

            self::sendSmtpCommand($socket, 'QUIT');

            return [
                'success' => true,
                'message' => 'Email sent successfully.',
            ];
        } catch (Throwable $exception) {
            $result = [
                'success' => false,
                'message' => 'Email send failed: ' . $exception->getMessage(),
            ];
            self::logMailFailure($recipient, $subject, $result['message']);
            return $result;
        } finally {
            fclose($socket);
        }
    }

    /**
     * @param resource $socket
     */
    private static function sendSmtpCommand($socket, string $command): void
    {
        fwrite($socket, $command . "\r\n");
    }

    /**
     * @param resource $socket
     * @param int[] $expectedCodes
     */
    private static function expectSmtpCode($socket, array $expectedCodes, string $stage): void
    {
        $response = '';
        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;
            if (strlen($line) < 4 || $line[3] !== '-') {
                break;
            }
        }

        $code = (int) substr(trim($response), 0, 3);
        if (!in_array($code, $expectedCodes, true)) {
            throw new RuntimeException($stage . ' failed. Server response: ' . trim($response));
        }
    }

    private static function encodeHeader(string $value): string
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            return '';
        }

        if (preg_match('/[\x80-\xFF]/', $trimmed) === 1) {
            return '=?UTF-8?B?' . base64_encode($trimmed) . '?=';
        }

        return $trimmed;
    }

    private static function logMailFailure(string $recipient, string $subject, string $error): void
    {
        $logDir = APP_ROOT . 'storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }

        $line = '[' . date('Y-m-d H:i:s') . '] MAIL_FAIL to=' . trim($recipient) . ' subject="' . trim($subject) . '" error=' . trim($error) . PHP_EOL;
        @file_put_contents($logDir . '/mail.log', $line, FILE_APPEND);
    }
}
