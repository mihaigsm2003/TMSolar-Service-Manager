<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Comenzi Service</h1>
        <p class="text-muted mb-0">Urmărește solicitările de reparație de la recepție la livrare.</p>
    </div>
    <a href="<?= UrlHelper::to('service-orders/create') ?>" class="btn btn-primary">Adaugă Comandă Service</a>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-8">
        <input type="text" class="form-control" name="search" placeholder="Caută după număr comandă, client sau defect" value="<?= htmlspecialchars((string) $search) ?>">
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
                    <th>Comandă #</th>
                    <th>Dată</th>
                    <th>Client</th>
                    <th>Model</th>
                    <th>Serie</th>
                    <th>Tip Comandă</th>
                    <th>Transport</th>
                    <th>Prioritate</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <?php
                        $priorityLabels = [
                            'Normal' => 'Normală',
                            'High' => 'Ridicată',
                            'Urgent' => 'Urgentă',
                        ];
                        $statusLabels = [
                            'Noua' => 'Noua',
                            'Acceptate' => 'Acceptate',
                            'Receptionate' => 'Receptionate',
                            'In service' => 'In service',
                            'Asteptare Confirmare' => 'Asteptare Confirmare',
                            'Confirmate' => 'Confirmate',
                            'Constatare' => 'Constatare',
                            'Finalizate' => 'Finalizate',
                            'Expediate' => 'Expediate',
                            'Anulate' => 'Anulate',
                            'Nereparabil' => 'Nereparabil',
                            'Received' => 'Receptionate',
                            'Waiting Diagnosis' => 'Constatare',
                            'Waiting Customer' => 'Asteptare Confirmare',
                            'Waiting Parts' => 'Asteptare Confirmare',
                            'Repairing' => 'In service',
                            'Testing' => 'In service',
                            'Ready' => 'Finalizate',
                            'Shipped' => 'Expediate',
                            'Delivered' => 'Finalizate',
                            'Cancelled' => 'Anulate',
                        ];
                        $priorityRaw = (string) ($order['priority'] ?? 'Normal');
                        $statusRaw = (string) ($order['status'] ?? 'Noua');
                        $commandTypeRaw = trim((string) ($order['command_type'] ?? ''));
                        $isWarrantyRepair = !empty($order['warranty_repair']);
                        if ($isWarrantyRepair && ($commandTypeRaw === '' || $commandTypeRaw === 'Service')) {
                            $commandTypeDisplay = 'Service Garantie';
                        } elseif ($commandTypeRaw !== '') {
                            $commandTypeDisplay = $commandTypeRaw;
                        } else {
                            $commandTypeDisplay = 'Service';
                        }

                        $transportTypeRaw = trim((string) ($order['transport_type'] ?? ''));
                        $notesTransport = '';
                        if (preg_match('/Transport:\s*(Curier|Personal)/iu', (string) ($order['internal_notes'] ?? ''), $matches) === 1) {
                            $notesTransport = trim((string) ($matches[1] ?? ''));
                        }
                        if ($transportTypeRaw === '' || $transportTypeRaw === 'Curier') {
                            $transportTypeDisplay = $notesTransport !== '' ? $notesTransport : ($transportTypeRaw !== '' ? $transportTypeRaw : '-');
                        } else {
                            $transportTypeDisplay = $transportTypeRaw;
                        }

                        $deviceModelDisplay = trim((string) ($order['device_model_name'] ?? ''));
                        if ($deviceModelDisplay === '') {
                            $deviceModelDisplay = '-';
                        }

                        $deviceSerialDisplay = trim((string) ($order['serial_number'] ?? ''));
                        if ($deviceSerialDisplay === '') {
                            $deviceSerialDisplay = '-';
                        }

                    ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($order['order_number'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($order['received_date'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string) ($order['first_name'] ?? '') . ' ' . (string) ($order['last_name'] ?? '')) ?></td>
                        <td><?= htmlspecialchars($deviceModelDisplay) ?></td>
                        <td><?= htmlspecialchars($deviceSerialDisplay) ?></td>
                        <td><?= htmlspecialchars($commandTypeDisplay) ?></td>
                        <td><?= htmlspecialchars($transportTypeDisplay) ?></td>
                        <td><?= htmlspecialchars($priorityLabels[$priorityRaw] ?? $priorityRaw) ?></td>
                        <td><?= htmlspecialchars($statusLabels[$statusRaw] ?? $statusRaw) ?></td>
                        <td><?= number_format(((float) ($order['repair_cost'] ?? 0) + (float) ($order['shipping_cost'] ?? 0)), 2) ?></td>
                        <td>
                            <a href="<?= UrlHelper::to('service-orders/edit/' . (int) $order['id']) ?>" class="btn btn-sm btn-outline-primary">Editează</a>
                            <a href="<?= UrlHelper::to('service-orders/delete/' . (int) $order['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ștergi această comandă service?')">Șterge</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
