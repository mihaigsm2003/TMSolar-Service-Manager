<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Devices</h1>
        <p class="text-muted mb-0">Register customer devices, serial numbers, and warranty details.</p>
    </div>
    <a href="<?= UrlHelper::to('devices/create') ?>" class="btn btn-primary">Add Device</a>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-8">
        <input type="text" class="form-control" name="search" placeholder="Search by serial number or customer" value="<?= htmlspecialchars((string) $search) ?>">
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
                    <th>Customer</th>
                    <th>Manufacturer</th>
                    <th>Model</th>
                    <th>Serial Number</th>
                    <th>Warranty</th>
                    <th>Purchase Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($devices as $device): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($device['first_name'] ?? '') . ' ' . (string) ($device['last_name'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($device['manufacturer_name'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($device['model_name'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($device['serial_number'] ?? '')) ?></td>
                        <td><?= !empty($device['warranty']) ? 'Yes' : 'No' ?></td>
                        <td><?= htmlspecialchars((string) ($device['purchase_date'] ?? '')) ?></td>
                        <td>
                            <a href="<?= UrlHelper::to('devices/edit/' . (int) $device['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="<?= UrlHelper::to('devices/delete/' . (int) $device['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this device?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
