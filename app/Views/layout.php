<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) ($pageTitle ?? APP_NAME)) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= UrlHelper::asset('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <?php $user = $_SESSION['user'] ?? []; ?>
    <?php
    $brandSettings = $settings ?? [];
    $brandText = APP_NAME;
    $brandLogo = '';

    if (empty($brandSettings)) {
        try {
            $brandDb = Database::getInstance();
            $brandSettings = [];
            foreach ($brandDb->query('SELECT setting_key, setting_value FROM settings') as $row) {
                $brandSettings[(string) $row['setting_key']] = (string) $row['setting_value'];
            }
        } catch (Throwable $exception) {
            $brandSettings = [];
        }
    }

    if (!empty($brandSettings)) {
        $brandText = trim((string) ($brandSettings['navbar_brand_text'] ?? $brandSettings['company_name'] ?? 'TMSolar Service Manager'));
        $brandLogo = trim((string) ($brandSettings['navbar_logo_url'] ?? ''));
        if ($brandLogo !== '' && !preg_match('#^https?://#i', $brandLogo)) {
            $brandLogo = UrlHelper::asset($brandLogo);
        }
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= UrlHelper::to('dashboard') ?>">
                <?php if ($brandLogo !== ''): ?>
                    <img src="<?= htmlspecialchars($brandLogo) ?>" alt="<?= htmlspecialchars($brandText ?: 'Brand Logo') ?>" height="32" class="d-inline-block">
                <?php endif; ?>
                <?php if ($brandText !== ''): ?>
                    <span><?= htmlspecialchars($brandText) ?></span>
                <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (!empty($_SESSION['user'])): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('dashboard') ?>">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('customers') ?>">Customers</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('manufacturers') ?>">Manufacturers</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('device-models') ?>">Device Models</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('devices') ?>">Devices</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('service-orders') ?>">Service Orders</a></li>
                        <?php if (($user['role'] ?? 'viewer') === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('admin/settings') ?>">Settings</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('admin/users') ?>">Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('admin/activity-log') ?>">Activity Log</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('about.php') ?>">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('logout') ?>">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('about.php') ?>">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('login') ?>">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        <?php if (isset($contentView) && is_file($contentView)) { require $contentView; } ?>
    </main>

    <footer class="app-footer">
        <div class="container">
            <?php
            $appName = defined('APP_NAME') ? APP_NAME : 'TMSolar Service Manager';
            $appVersion = defined('APP_VERSION') ? APP_VERSION : '1.0.0';
            $appAuthor = defined('APP_AUTHOR') ? APP_AUTHOR : 'Mihai Taranu. - TMSolar @mihaigsm2003';
            $authorParts = explode(' - ', $appAuthor, 2);
            $authorName = $authorParts[0] ?? $appAuthor;
            $authorOrg = $authorParts[1] ?? 'TMSolar';
            ?>
            <span><?= htmlspecialchars($appName) ?> v<?= htmlspecialchars($appVersion) ?> | © <?= date('Y') ?> <?= htmlspecialchars($authorOrg) ?> | Developed by <?= htmlspecialchars($authorName) ?></span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= UrlHelper::asset('assets/js/app.js') ?>"></script>
</body>
</html>
