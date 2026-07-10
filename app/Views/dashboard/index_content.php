<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">Welcome, <?= htmlspecialchars((string) $user['name']) ?>!</h1>
                <p class="text-muted">TMSolar Service Manager is now equipped for customer, device, and service order workflows.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <h2 class="h5">Customers</h2>
                            <p class="text-muted mb-0">Store customer details, company info, and contact records.</p>
                            <div class="display-6 mt-3"><?= (int) ($stats['customers'] ?? 0) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <h2 class="h5">Service Orders</h2>
                            <p class="text-muted mb-0">Track repair progress from receipt to shipment and delivery.</p>
                            <div class="display-6 mt-3"><?= (int) ($stats['orders'] ?? 0) ?></div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?= UrlHelper::to('customers') ?>" class="btn btn-outline-primary me-2">Customers</a>
                    <a href="<?= UrlHelper::to('manufacturers') ?>" class="btn btn-outline-primary me-2">Manufacturers</a>
                    <a href="<?= UrlHelper::to('devices') ?>" class="btn btn-outline-primary me-2">Devices</a>
                    <a href="<?= UrlHelper::to('service-orders') ?>" class="btn btn-outline-primary">Service Orders</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h5">Quick Summary</h2>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">Open Orders: <?= (int) ($stats['open_orders'] ?? 0) ?></li>
                    <li class="list-group-item px-0">Devices: <?= (int) ($stats['devices'] ?? 0) ?></li>
                    <li class="list-group-item px-0">Manufacturers: <?= (int) ($stats['manufacturers'] ?? 0) ?></li>
                </ul>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5">Recent Activity</h2>
                <ul class="list-group list-group-flush">
                    <?php foreach (($stats['recent_activity'] ?? []) as $entry): ?>
                        <li class="list-group-item px-0 small">
                            <strong><?= htmlspecialchars((string) ($entry['action'] ?? 'Activity')) ?></strong><br>
                            <span class="text-muted"><?= htmlspecialchars((string) ($entry['details'] ?? '')) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
