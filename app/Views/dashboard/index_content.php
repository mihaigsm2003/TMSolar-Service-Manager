<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3">Bun venit, <?= htmlspecialchars((string) $user['name']) ?>!</h1>
                <p class="text-muted">TMSolar Service Manager este pregătit pentru fluxurile de lucru cu clienți, dispozitive și comenzi service.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <h2 class="h5">Clienți</h2>
                            <p class="text-muted mb-0">Stochează datele clienților, informațiile companiei și datele de contact.</p>
                            <div class="display-6 mt-3"><?= (int) ($stats['customers'] ?? 0) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <h2 class="h5">Comenzi Service</h2>
                            <p class="text-muted mb-0">Urmărește progresul reparațiilor de la recepție la expediere și livrare.</p>
                            <div class="display-6 mt-3"><?= (int) ($stats['orders'] ?? 0) ?></div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?= UrlHelper::to('customers') ?>" class="btn btn-outline-primary me-2">Clienți</a>
                    <a href="<?= UrlHelper::to('manufacturers') ?>" class="btn btn-outline-primary me-2">Producători</a>
                    <a href="<?= UrlHelper::to('devices') ?>" class="btn btn-outline-primary me-2">Dispozitive</a>
                    <a href="<?= UrlHelper::to('service-orders') ?>" class="btn btn-outline-primary">Comenzi Service</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h5">Rezumat Rapid</h2>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">Comenzi Deschise: <?= (int) ($stats['open_orders'] ?? 0) ?></li>
                    <li class="list-group-item px-0">Dispozitive: <?= (int) ($stats['devices'] ?? 0) ?></li>
                    <li class="list-group-item px-0">Producători: <?= (int) ($stats['manufacturers'] ?? 0) ?></li>
                </ul>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5">Activitate Recentă</h2>
                <ul class="list-group list-group-flush">
                    <?php foreach (($stats['recent_activity'] ?? []) as $entry): ?>
                        <li class="list-group-item px-0 small">
                            <strong><?= htmlspecialchars((string) ($entry['action'] ?? 'Activitate')) ?></strong><br>
                            <span class="text-muted"><?= htmlspecialchars((string) ($entry['details'] ?? '')) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
