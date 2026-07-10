<?php require __DIR__ . '/../layout.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Settings</h1>
            <p class="text-muted mb-0">Configure the main application preferences.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
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
                </div>
                <button type="submit" class="btn btn-primary mt-4">Save Settings</button>
            </form>
        </div>
    </div>
</div>
