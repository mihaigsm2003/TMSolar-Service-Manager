<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Setări</h1>
            <p class="text-muted mb-0">Configurează preferințele principale ale aplicației.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (!empty($mailTestResult)): ?>
                <div class="alert <?= !empty($mailTestResult['success']) ? 'alert-success' : 'alert-danger' ?>" role="alert">
                    <?= htmlspecialchars((string) ($mailTestResult['message'] ?? '')) ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nume Companie</label>
                        <input type="text" class="form-control" name="settings[company_name]" value="<?= htmlspecialchars((string) ($settings['company_name'] ?? 'TMSolar Service Manager')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Suport</label>
                        <input type="email" class="form-control" name="settings[support_email]" value="<?= htmlspecialchars((string) ($settings['support_email'] ?? 'support@example.com')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prioritate Implicită</label>
                        <input type="text" class="form-control" name="settings[default_priority]" value="<?= htmlspecialchars((string) ($settings['default_priority'] ?? 'Normal')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Folder Backup</label>
                        <input type="text" class="form-control" name="settings[backup_folder]" value="<?= htmlspecialchars((string) ($settings['backup_folder'] ?? 'storage/backups')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Text Brand Navbar</label>
                        <input type="text" class="form-control" name="settings[navbar_brand_text]" value="<?= htmlspecialchars((string) ($settings['navbar_brand_text'] ?? 'Logo')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">URL Logo Navbar</label>
                        <input type="text" class="form-control" name="settings[navbar_logo_url]" value="<?= htmlspecialchars((string) ($settings['navbar_logo_url'] ?? '')) ?>" placeholder="assets/img/logo.png or https://example.com/logo.png">
                        <div class="form-text">Lasă gol pentru a afișa doar text. Folosește o cale relativă sau URL complet.</div>
                    </div>
                </div>

                <h2 class="h5 mt-4 mb-3">Server Email</h2>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="emailNotificationsEnabled" name="settings[email_notifications_enabled]" value="1" <?= (($settings['email_notifications_enabled'] ?? '1') === '1') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="emailNotificationsEnabled">Activează notificările email</label>
                        </div>
                        <div class="form-text">Când este dezactivat, emailurile automate de notificare nu se trimit.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gazdă SMTP</label>
                        <input type="text" class="form-control" name="settings[mail_host]" value="<?= htmlspecialchars((string) ($settings['mail_host'] ?? 'smtp.yourserver.com')) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Port SMTP</label>
                        <input type="number" class="form-control" name="settings[mail_port]" value="<?= htmlspecialchars((string) ($settings['mail_port'] ?? '587')) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Criptare</label>
                        <input type="text" class="form-control" name="settings[mail_encryption]" value="<?= htmlspecialchars((string) ($settings['mail_encryption'] ?? 'tls')) ?>" placeholder="tls/ssl/none">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Utilizator SMTP</label>
                        <input type="text" class="form-control" name="settings[mail_username]" value="<?= htmlspecialchars((string) ($settings['mail_username'] ?? 'your-email@yourdomain.com')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Parolă SMTP</label>
                        <input type="password" class="form-control" name="settings[mail_password]" value="<?= htmlspecialchars((string) ($settings['mail_password'] ?? 'your-password')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Expeditor</label>
                        <input type="email" class="form-control" name="settings[mail_from_address]" value="<?= htmlspecialchars((string) ($settings['mail_from_address'] ?? 'no-reply@yourdomain.com')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nume Expeditor</label>
                        <input type="text" class="form-control" name="settings[mail_from_name]" value="<?= htmlspecialchars((string) ($settings['mail_from_name'] ?? 'TMSolar Service Manager')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Destinatar Test</label>
                        <input type="email" class="form-control" name="test_email_to" value="<?= htmlspecialchars((string) ($settings['support_email'] ?? $settings['mail_from_address'] ?? '')) ?>" placeholder="recipient@example.com">
                        <div class="form-text">Folosit doar pentru verificarea serverului. Nu se salvează în setări.</div>
                    </div>
                </div>

                <h2 class="h5 mt-4 mb-3">reCAPTCHA</h2>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="recaptchaEnabled" name="settings[recaptcha_enabled]" value="1" <?= (($settings['recaptcha_enabled'] ?? '0') === '1') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="recaptchaEnabled">Activează reCAPTCHA pentru Comandă Online</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">reCAPTCHA Site Key</label>
                        <input type="text" class="form-control" name="settings[recaptcha_site_key]" value="<?= htmlspecialchars((string) ($settings['recaptcha_site_key'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">reCAPTCHA Secret Key</label>
                        <input type="password" class="form-control" name="settings[recaptcha_secret_key]" value="<?= htmlspecialchars((string) ($settings['recaptcha_secret_key'] ?? '')) ?>">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" name="settings_action" value="save" class="btn btn-primary">Salvează Setările</button>
                    <button type="submit" name="settings_action" value="test_email" class="btn btn-outline-secondary">Testează Setările Email</button>
                </div>
            </form>
        </div>
    </div>
</div>