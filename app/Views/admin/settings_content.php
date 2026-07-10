<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Settings</h1>
            <p class="text-muted mb-0">Configure the main application preferences.</p>
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
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" name="settings[company_name]" value="<?= htmlspecialchars((string) ($settings['company_name'] ?? 'TMSolar Service Manager')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Support Email</label>
                        <input type="email" class="form-control" name="settings[support_email]" value="<?= htmlspecialchars((string) ($settings['support_email'] ?? 'support@example.com')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Default Priority</label>
                        <input type="text" class="form-control" name="settings[default_priority]" value="<?= htmlspecialchars((string) ($settings['default_priority'] ?? 'Normal')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Backup Folder</label>
                        <input type="text" class="form-control" name="settings[backup_folder]" value="<?= htmlspecialchars((string) ($settings['backup_folder'] ?? 'storage/backups')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Navbar Brand Text</label>
                        <input type="text" class="form-control" name="settings[navbar_brand_text]" value="<?= htmlspecialchars((string) ($settings['navbar_brand_text'] ?? 'Logo')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Navbar Logo URL</label>
                        <input type="text" class="form-control" name="settings[navbar_logo_url]" value="<?= htmlspecialchars((string) ($settings['navbar_logo_url'] ?? '')) ?>" placeholder="assets/img/logo.png or https://example.com/logo.png">
                        <div class="form-text">Leave blank to show text only. Use a relative path or a full URL.</div>
                    </div>
                </div>

                <h2 class="h5 mt-4 mb-3">Email Server</h2>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="emailNotificationsEnabled" name="settings[email_notifications_enabled]" value="1" <?= (($settings['email_notifications_enabled'] ?? '1') === '1') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="emailNotificationsEnabled">Enable email notifications</label>
                        </div>
                        <div class="form-text">When disabled, automatic notification emails are skipped.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SMTP Host</label>
                        <input type="text" class="form-control" name="settings[mail_host]" value="<?= htmlspecialchars((string) ($settings['mail_host'] ?? 'smtp.yourserver.com')) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">SMTP Port</label>
                        <input type="number" class="form-control" name="settings[mail_port]" value="<?= htmlspecialchars((string) ($settings['mail_port'] ?? '587')) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Encryption</label>
                        <input type="text" class="form-control" name="settings[mail_encryption]" value="<?= htmlspecialchars((string) ($settings['mail_encryption'] ?? 'tls')) ?>" placeholder="tls/ssl/none">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SMTP Username</label>
                        <input type="text" class="form-control" name="settings[mail_username]" value="<?= htmlspecialchars((string) ($settings['mail_username'] ?? 'your-email@yourdomain.com')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SMTP Password</label>
                        <input type="password" class="form-control" name="settings[mail_password]" value="<?= htmlspecialchars((string) ($settings['mail_password'] ?? 'your-password')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From Email</label>
                        <input type="email" class="form-control" name="settings[mail_from_address]" value="<?= htmlspecialchars((string) ($settings['mail_from_address'] ?? 'no-reply@yourdomain.com')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From Name</label>
                        <input type="text" class="form-control" name="settings[mail_from_name]" value="<?= htmlspecialchars((string) ($settings['mail_from_name'] ?? 'TMSolar Service Manager')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Test Recipient Email</label>
                        <input type="email" class="form-control" name="test_email_to" value="<?= htmlspecialchars((string) ($settings['support_email'] ?? $settings['mail_from_address'] ?? '')) ?>" placeholder="recipient@example.com">
                        <div class="form-text">Used only for server verification. It is not saved in settings.</div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" name="settings_action" value="save" class="btn btn-primary">Save Settings</button>
                    <button type="submit" name="settings_action" value="test_email" class="btn btn-outline-secondary">Test Email Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>