<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Modele Dispozitive</h1>
        <p class="text-muted mb-0">Gestionează modelele suportate de invertoare fotovoltaice.</p>
    </div>
    <a href="<?= UrlHelper::to('device-models/create') ?>" class="btn btn-primary">Adaugă Model Dispozitiv</a>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-8">
        <input type="text" class="form-control" name="search" placeholder="Caută după model sau producător" value="<?= htmlspecialchars((string) $search) ?>">
    </div>
    <div class="col-md-4">
        <button class="btn btn-outline-secondary w-100" type="submit">Caută</button>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Producător</th>
                    <th>Model</th>
                    <th>Descriere</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($model['manufacturer_name'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($model['name'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($model['description'] ?? '')) ?></td>
                        <td>
                            <a href="<?= UrlHelper::to('device-models/edit/' . (int) $model['id']) ?>" class="btn btn-sm btn-outline-primary">Editează</a>
                            <a href="<?= UrlHelper::to('device-models/delete/' . (int) $model['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ștergi acest model?')">Șterge</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
