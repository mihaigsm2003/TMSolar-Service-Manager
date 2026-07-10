<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) ($pageTitle ?? APP_NAME)) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= UrlHelper::asset('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
    <?php $user = $_SESSION['user'] ?? []; ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= UrlHelper::to('dashboard') ?>">TMSolar</a>
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
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('logout') ?>">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= UrlHelper::to('login') ?>">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <?php if (isset($contentView) && is_file($contentView)) { require $contentView; } ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= UrlHelper::asset('assets/js/app.js') ?>"></script>
</body>
</html>
