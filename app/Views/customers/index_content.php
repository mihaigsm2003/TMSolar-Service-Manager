<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Customers</h1>
        <p class="text-muted mb-0">Manage customer accounts and contact information.</p>
    </div>
    <a href="<?= UrlHelper::to('customers/create') ?>" class="btn btn-primary">Add Customer</a>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-8">
        <input type="text" class="form-control" name="search" placeholder="Search by name, company, phone or email" value="<?= htmlspecialchars((string) $search) ?>">
    </div>
    <div class="col-md-4">
        <button class="btn btn-outline-secondary w-100" type="submit">Search</button>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>VAT</th>
                    <th>Address</th>
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
                            <a href="<?= UrlHelper::to('customers/edit/' . (int) $customer['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="<?= UrlHelper::to('customers/delete/' . (int) $customer['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this customer?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
