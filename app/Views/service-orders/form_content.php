<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3"><?= htmlspecialchars((string) ($pageTitle ?? 'Comandă Service')) ?></h1>
                <form method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Număr Comandă</label>
                            <input type="text" class="form-control" name="order_number" value="<?= htmlspecialchars((string) ($order['order_number'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data Recepției</label>
                            <input type="date" class="form-control" name="received_date" value="<?= htmlspecialchars((string) ($order['received_date'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Client</label>
                            <select class="form-select" name="customer_id" required>
                                <option value="">Alege client</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= (int) $customer['id'] ?>" <?= ((int) ($order['customer_id'] ?? 0) === (int) $customer['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string) ($customer['first_name'] ?? '') . ' ' . (string) ($customer['last_name'] ?? '')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dispozitiv</label>
                            <select class="form-select" name="device_id" required>
                                <option value="">Alege dispozitiv</option>
                                <?php foreach ($devices as $device): ?>
                                    <?php
                                        $modelName = trim((string) ($device['model_name'] ?? ''));
                                        $serialNumber = trim((string) ($device['serial_number'] ?? ''));
                                        $deviceLabel = $modelName !== '' ? ($modelName . ' / ' . $serialNumber) : $serialNumber;
                                    ?>
                                    <option value="<?= (int) $device['id'] ?>" <?= ((int) ($order['device_id'] ?? 0) === (int) $device['id']) ? 'selected' : '' ?>><?= htmlspecialchars($deviceLabel !== '' ? $deviceLabel : ('Dispozitiv #' . (int) $device['id'])) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tip Comandă</label>
                            <?php
                                $commandTypeRaw = trim((string) ($order['command_type'] ?? ''));
                                $isWarrantyRepair = !empty($order['warranty_repair']);
                                if ($isWarrantyRepair && ($commandTypeRaw === '' || $commandTypeRaw === 'Service')) {
                                    $commandType = 'Service Garantie';
                                } elseif ($commandTypeRaw !== '') {
                                    $commandType = $commandTypeRaw;
                                } else {
                                    $commandType = 'Service';
                                }
                            ?>
                            <select class="form-select" name="command_type" required>
                                <option value="Service" <?= $commandType === 'Service' ? 'selected' : '' ?>>Service</option>
                                <option value="Service Garantie" <?= $commandType === 'Service Garantie' ? 'selected' : '' ?>>Service Garanție</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tip Transport</label>
                            <?php
                                $transportTypeRaw = trim((string) ($order['transport_type'] ?? ''));
                                $notesTransport = '';
                                if (preg_match('/Transport:\s*(Curier|Personal)/iu', (string) ($order['internal_notes'] ?? ''), $matches) === 1) {
                                    $notesTransport = trim((string) ($matches[1] ?? ''));
                                }
                                if ($transportTypeRaw === '' || $transportTypeRaw === 'Curier') {
                                    $transportType = $notesTransport !== '' ? $notesTransport : ($transportTypeRaw !== '' ? $transportTypeRaw : 'Curier');
                                } else {
                                    $transportType = $transportTypeRaw;
                                }
                            ?>
                            <select class="form-select" name="transport_type" required>
                                <option value="Curier" <?= $transportType === 'Curier' ? 'selected' : '' ?>>Curier</option>
                                <option value="Personal" <?= $transportType === 'Personal' ? 'selected' : '' ?>>Personal</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="warrantyRepairDateContainer" style="<?= $commandType === 'Service Garantie' ? '' : 'display:none;' ?>">
                            <label class="form-label">Data Reparației (Garanție)</label>
                            <input type="date" class="form-control" name="warranty_repair_date" id="warranty_repair_date" value="<?= htmlspecialchars((string) ($order['warranty_repair_date'] ?? '')) ?>" <?= $commandType === 'Service Garantie' ? '' : 'disabled' ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Defect Reclamat</label>
                            <textarea class="form-control" name="reported_fault" rows="3"><?= htmlspecialchars((string) ($order['reported_fault'] ?? '')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Defect Diagnosticat</label>
                            <textarea class="form-control" name="diagnosed_fault" rows="3"><?= htmlspecialchars((string) ($order['diagnosed_fault'] ?? '')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Note Reparație</label>
                            <textarea class="form-control" name="repair_notes" rows="3"><?= htmlspecialchars((string) ($order['repair_notes'] ?? '')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Note Interne</label>
                            <textarea class="form-control" name="internal_notes" rows="3"><?= htmlspecialchars((string) ($order['internal_notes'] ?? '')) ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Prioritate</label>
                            <select class="form-select" name="priority">
                                <option value="Normal" <?= (($order['priority'] ?? 'Normal') === 'Normal') ? 'selected' : '' ?>>Normală</option>
                                <option value="High" <?= (($order['priority'] ?? 'Normal') === 'High') ? 'selected' : '' ?>>Ridicată</option>
                                <option value="Urgent" <?= (($order['priority'] ?? 'Normal') === 'Urgent') ? 'selected' : '' ?>>Urgentă</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="Noua" <?= (($order['status'] ?? 'Noua') === 'Noua') ? 'selected' : '' ?>>Noua</option>
                                <option value="Acceptate" <?= (($order['status'] ?? 'Noua') === 'Acceptate') ? 'selected' : '' ?>>Acceptate</option>
                                <option value="Receptionate" <?= (($order['status'] ?? 'Noua') === 'Receptionate') ? 'selected' : '' ?>>Receptionate</option>
                                <option value="In service" <?= (($order['status'] ?? 'Noua') === 'In service') ? 'selected' : '' ?>>In service</option>
                                <option value="Asteptare Confirmare" <?= (($order['status'] ?? 'Noua') === 'Asteptare Confirmare') ? 'selected' : '' ?>>Asteptare Confirmare</option>
                                <option value="Confirmate" <?= (($order['status'] ?? 'Noua') === 'Confirmate') ? 'selected' : '' ?>>Confirmate</option>
                                <option value="Constatare" <?= (($order['status'] ?? 'Noua') === 'Constatare') ? 'selected' : '' ?>>Constatare</option>
                                <option value="Finalizate" <?= (($order['status'] ?? 'Noua') === 'Finalizate') ? 'selected' : '' ?>>Finalizate</option>
                                <option value="Expediate" <?= (($order['status'] ?? 'Noua') === 'Expediate') ? 'selected' : '' ?>>Expediate</option>
                                <option value="Anulate" <?= (($order['status'] ?? 'Noua') === 'Anulate') ? 'selected' : '' ?>>Anulate</option>
                                <option value="Nereparabil" <?= (($order['status'] ?? 'Noua') === 'Nereparabil') ? 'selected' : '' ?>>Nereparabil</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cost Reparație</label>
                            <input type="number" step="0.01" class="form-control" name="repair_cost" value="<?= htmlspecialchars((string) ($order['repair_cost'] ?? '0')) ?>">
                        </div>
                        <div class="col-md-4" id="shippingCostContainer" style="<?= $transportType === 'Curier' ? '' : 'display:none;' ?>">
                            <label class="form-label">Cost Transport</label>
                            <input type="number" step="0.01" class="form-control" name="shipping_cost" id="shipping_cost" value="<?= htmlspecialchars((string) ($order['shipping_cost'] ?? '0')) ?>" <?= $transportType === 'Curier' ? '' : 'disabled' ?>>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Fotografii</label>
                            <input type="file" class="form-control" name="photos">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Documente</label>
                            <input type="file" class="form-control" name="documents">
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Salvează</button>
                        <a href="<?= UrlHelper::to('service-orders') ?>" class="btn btn-outline-secondary">Anulează</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const commandTypeSelect = document.querySelector('select[name="command_type"]');
        const transportTypeSelect = document.querySelector('select[name="transport_type"]');
        const warrantyContainer = document.getElementById('warrantyRepairDateContainer');
        const warrantyInput = document.getElementById('warranty_repair_date');
        const shippingContainer = document.getElementById('shippingCostContainer');
        const shippingInput = document.getElementById('shipping_cost');

        if (!commandTypeSelect || !transportTypeSelect || !warrantyContainer || !warrantyInput || !shippingContainer || !shippingInput) {
            return;
        }

        function syncWarrantyVisibility() {
            const isWarranty = commandTypeSelect.value === 'Service Garantie';
            warrantyContainer.style.display = isWarranty ? '' : 'none';
            warrantyInput.disabled = !isWarranty;
            if (!isWarranty) {
                warrantyInput.value = '';
            }
        }

        function syncShippingVisibility() {
            const isCurier = transportTypeSelect.value === 'Curier';
            shippingContainer.style.display = isCurier ? '' : 'none';
            shippingInput.disabled = !isCurier;
            if (!isCurier) {
                shippingInput.value = '0';
            }
        }

        commandTypeSelect.addEventListener('change', syncWarrantyVisibility);
        transportTypeSelect.addEventListener('change', syncShippingVisibility);

        syncWarrantyVisibility();
        syncShippingVisibility();
    })();
</script>
