<div class="card shadow-sm">
    <div class="card-body">
        <?php
        $appName = defined('APP_NAME') ? APP_NAME : 'TMSolar Service Manager';
        $appVersion = defined('APP_VERSION') ? APP_VERSION : '1.0.1';
        $appAuthor = defined('APP_AUTHOR') ? APP_AUTHOR : 'Mihai G. - TMSolar';
        ?>
        <h1 class="h3 mb-4">About</h1>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h2 class="h5 mb-3">Application Details</h2>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Application Name:</strong> <?= htmlspecialchars($appName) ?></li>
                        <li><strong>Version:</strong> <?= htmlspecialchars($appVersion) ?></li>
                        <li><strong>Build Date:</strong> <?= date('Y-m-d') ?></li>
                        <li><strong>Author:</strong> <?= htmlspecialchars($appAuthor) ?></li>
                        <li><strong>Website:</strong> <a href="https://tmsolar.ro" target="_blank" rel="noopener noreferrer">tmsolar.ro</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h2 class="h5 mb-3">Environment</h2>
                    <ul class="list-unstyled mb-0">
                        <li><strong>PHP Version:</strong> <?= htmlspecialchars(PHP_VERSION) ?></li>
                        <li><strong>Database Driver:</strong> <?= htmlspecialchars(PDO::getAvailableDrivers()[0] ?? 'Unavailable') ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
