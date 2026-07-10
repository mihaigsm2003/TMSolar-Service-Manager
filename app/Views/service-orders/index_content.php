<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Service Orders</h1>
        <p class="text-muted mb-0">Track repair requests from receipt to delivery.</p>
    </div>
    <a href="<?= UrlHelper::to('service-orders/create') ?>" class="btn btn-primary">Add Service Order</a>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-8">
        <input type="text" class="form-control" name="search" placeholder="Search by order number, customer or fault" value="<?= htmlspecialchars((string) $search) ?>">
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
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Device</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($order['order_number'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($order['received_date'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($order['first_name'] ?? '') . ' ' . (string) ($order['last_name'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($order['serial_number'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($order['priority'] ?? 'Normal')) ?></td>
                        <td><?= htmlspecialchars((string) ($order['status'] ?? 'Received')) ?></td>
                        <td><?= number_format((float) ($order['total_cost'] ?? 0), 2) ?></td>
                        <td>
                            <a href="<?= UrlHelper::to('service-orders/edit/' . (int) $order['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="<?= UrlHelper::to('service-orders/delete/' . (int) $order['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this service order?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
