<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3"><?= htmlspecialchars((string) ($pageTitle ?? 'Service Order')) ?></h1>
                <form method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Order Number</label>
                            <input type="text" class="form-control" name="order_number" value="<?= htmlspecialchars((string) ($order['order_number'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Received Date</label>
                            <input type="date" class="form-control" name="received_date" value="<?= htmlspecialchars((string) ($order['received_date'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Customer</label>
                            <select class="form-select" name="customer_id" required>
                                <option value="">Choose customer</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= (int) $customer['id'] ?>" <?= ((int) ($order['customer_id'] ?? 0) === (int) $customer['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string) ($customer['first_name'] ?? '') . ' ' . (string) ($customer['last_name'] ?? '')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Device</label>
                            <select class="form-select" name="device_id" required>
                                <option value="">Choose device</option>
                                <?php foreach ($devices as $device): ?>
                                    <option value="<?= (int) $device['id'] ?>" <?= ((int) ($order['device_id'] ?? 0) === (int) $device['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string) ($device['serial_number'] ?? '')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Accessories</label>
                            <textarea class="form-control" name="accessories" rows="2"><?= htmlspecialchars((string) ($order['accessories'] ?? '')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reported Fault</label>
                            <textarea class="form-control" name="reported_fault" rows="3"><?= htmlspecialchars((string) ($order['reported_fault'] ?? '')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Diagnosed Fault</label>
                            <textarea class="form-control" name="diagnosed_fault" rows="3"><?= htmlspecialchars((string) ($order['diagnosed_fault'] ?? '')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Repair Notes</label>
                            <textarea class="form-control" name="repair_notes" rows="3"><?= htmlspecialchars((string) ($order['repair_notes'] ?? '')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Internal Notes</label>
                            <textarea class="form-control" name="internal_notes" rows="3"><?= htmlspecialchars((string) ($order['internal_notes'] ?? '')) ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Priority</label>
                            <select class="form-select" name="priority">
                                <option value="Normal" <?= (($order['priority'] ?? 'Normal') === 'Normal') ? 'selected' : '' ?>>Normal</option>
                                <option value="High" <?= (($order['priority'] ?? 'Normal') === 'High') ? 'selected' : '' ?>>High</option>
                                <option value="Urgent" <?= (($order['priority'] ?? 'Normal') === 'Urgent') ? 'selected' : '' ?>>Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="Received" <?= (($order['status'] ?? 'Received') === 'Received') ? 'selected' : '' ?>>Received</option>
                                <option value="Waiting Diagnosis" <?= (($order['status'] ?? 'Received') === 'Waiting Diagnosis') ? 'selected' : '' ?>>Waiting Diagnosis</option>
                                <option value="Waiting Customer" <?= (($order['status'] ?? 'Received') === 'Waiting Customer') ? 'selected' : '' ?>>Waiting Customer</option>
                                <option value="Waiting Parts" <?= (($order['status'] ?? 'Received') === 'Waiting Parts') ? 'selected' : '' ?>>Waiting Parts</option>
                                <option value="Repairing" <?= (($order['status'] ?? 'Received') === 'Repairing') ? 'selected' : '' ?>>Repairing</option>
                                <option value="Testing" <?= (($order['status'] ?? 'Received') === 'Testing') ? 'selected' : '' ?>>Testing</option>
                                <option value="Ready" <?= (($order['status'] ?? 'Received') === 'Ready') ? 'selected' : '' ?>>Ready</option>
                                <option value="Shipped" <?= (($order['status'] ?? 'Received') === 'Shipped') ? 'selected' : '' ?>>Shipped</option>
                                <option value="Delivered" <?= (($order['status'] ?? 'Received') === 'Delivered') ? 'selected' : '' ?>>Delivered</option>
                                <option value="Cancelled" <?= (($order['status'] ?? 'Received') === 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Warranty Repair</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="warranty_repair" value="1" <?= !empty($order['warranty_repair']) ? 'checked' : '' ?>>
                                <label class="form-check-label">Yes</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Repair Cost</label>
                            <input type="number" step="0.01" class="form-control" name="repair_cost" value="<?= htmlspecialchars((string) ($order['repair_cost'] ?? '0')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Labour Cost</label>
                            <input type="number" step="0.01" class="form-control" name="labour_cost" value="<?= htmlspecialchars((string) ($order['labour_cost'] ?? '0')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Shipping Cost</label>
                            <input type="number" step="0.01" class="form-control" name="shipping_cost" value="<?= htmlspecialchars((string) ($order['shipping_cost'] ?? '0')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Photos</label>
                            <input type="file" class="form-control" name="photos">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Documents</label>
                            <input type="file" class="form-control" name="documents">
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="<?= UrlHelper::to('service-orders') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
