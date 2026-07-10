<div class="card shadow-sm">
    <div class="card-body">
        <?php
        $appName = defined('APP_NAME') ? APP_NAME : 'TMSolar Service Manager';
        $appVersion = defined('APP_VERSION') ? APP_VERSION : '1.0.1';
        $appAuthor = defined('APP_AUTHOR') ? APP_AUTHOR : 'Mihai G. - TMSolar';
        ?>
        <h1 class="h3 mb-4">Despre</h1>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h2 class="h5 mb-3">Detalii Aplicație</h2>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Nume Aplicație:</strong> <?= htmlspecialchars($appName) ?></li>
                        <li><strong>Versiune:</strong> <?= htmlspecialchars($appVersion) ?></li>
                        <li><strong>Dată Build:</strong> <?= date('Y-m-d') ?></li>
                        <li><strong>Autor:</strong> <?= htmlspecialchars($appAuthor) ?></li>
                        <li><strong>Website:</strong> <a href="https://tmsolar.ro" target="_blank" rel="noopener noreferrer">tmsolar.ro</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h2 class="h5 mb-3">Mediu</h2>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Versiune PHP:</strong> <?= htmlspecialchars(PHP_VERSION) ?></li>
                        <li><strong>Driver Bază de Date:</strong> <?= htmlspecialchars(PDO::getAvailableDrivers()[0] ?? 'Indisponibil') ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
