<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Producători</h1>
        <p class="text-muted mb-0">Administrează evidența producătorilor de invertoare și componente.</p>
    </div>
    <a href="<?= UrlHelper::to('manufacturers/create') ?>" class="btn btn-primary">Adaugă Producător</a>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-8">
        <input type="text" class="form-control" name="search" placeholder="Caută producători" value="<?= htmlspecialchars((string) $search) ?>">
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
                    <th>Nume</th>
                    <th>Persoană Contact</th>
                    <th>Telefon</th>
                    <th>Email</th>
                    <th>Site Web</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($manufacturers as $manufacturer): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($manufacturer['name'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($manufacturer['contact_person'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($manufacturer['phone'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($manufacturer['email'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($manufacturer['website'] ?? '')) ?></td>
                        <td>
                            <a href="<?= UrlHelper::to('manufacturers/edit/' . (int) $manufacturer['id']) ?>" class="btn btn-sm btn-outline-primary">Editează</a>
                            <a href="<?= UrlHelper::to('manufacturers/delete/' . (int) $manufacturer['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ștergi acest producător?')">Șterge</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
