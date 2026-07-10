<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Clienți</h1>
        <p class="text-muted mb-0">Gestionează conturile clienților și informațiile de contact.</p>
    </div>
    <a href="<?= UrlHelper::to('customers/create') ?>" class="btn btn-primary">Adaugă Client</a>
</div>

<?php if ((string) ($_GET['delete_error'] ?? '') === 'linked_data'): ?>
    <div class="alert alert-warning">
        Clientul nu poate fi șters deoarece are comenzi sau dispozitive asociate.
    </div>
<?php endif; ?>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-8">
        <input type="text" class="form-control" name="search" placeholder="Caută după nume, companie, telefon sau email" value="<?= htmlspecialchars((string) $search) ?>">
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
                    <th>Telefon</th>
                    <th>Email</th>
                    <th>Companie</th>
                    <th>VAT</th>
                    <th>Adresă</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($customer['first_name'] ?? '') . ' ' . (string) ($customer['last_name'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($customer['phone'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($customer['email'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($customer['company'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($customer['vat_number'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($customer['address'] ?? '')) ?></td>
                        <td>
                            <a href="<?= UrlHelper::to('customers/edit/' . (int) $customer['id']) ?>" class="btn btn-sm btn-outline-primary">Editează</a>
                            <a href="<?= UrlHelper::to('customers/delete/' . (int) $customer['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ștergi acest client?')">Șterge</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
