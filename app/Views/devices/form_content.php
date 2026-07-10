<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3"><?= htmlspecialchars((string) ($pageTitle ?? 'Dispozitiv')) ?></h1>
                <form method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Client</label>
                            <select class="form-select" name="customer_id" required>
                                <option value="">Alege client</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= (int) $customer['id'] ?>" <?= ((int) ($device['customer_id'] ?? 0) === (int) $customer['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string) ($customer['first_name'] ?? '') . ' ' . (string) ($customer['last_name'] ?? '')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Producător</label>
                            <select class="form-select" name="manufacturer_id" required>
                                <option value="">Alege producător</option>
                                <?php foreach ($manufacturers as $manufacturer): ?>
                                    <option value="<?= (int) $manufacturer['id'] ?>" <?= ((int) ($device['manufacturer_id'] ?? 0) === (int) $manufacturer['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string) $manufacturer['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Model</label>
                            <select class="form-select" name="model_id" required>
                                <option value="">Alege model</option>
                                <?php foreach ($models as $model): ?>
                                    <option value="<?= (int) $model['id'] ?>" <?= ((int) ($device['model_id'] ?? 0) === (int) $model['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string) $model['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Serie</label>
                            <input type="text" class="form-control" name="serial_number" value="<?= htmlspecialchars((string) ($device['serial_number'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Garanție</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="warranty" value="1" <?= !empty($device['warranty']) ? 'checked' : '' ?>>
                                <label class="form-check-label">Acoperit de garanție</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data Achiziției</label>
                            <input type="date" class="form-control" name="purchase_date" value="<?= htmlspecialchars((string) ($device['purchase_date'] ?? '')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Fotografie</label>
                            <input type="file" class="form-control" name="photo">
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Salvează</button>
                        <a href="<?= UrlHelper::to('devices') ?>" class="btn btn-outline-secondary">Anulează</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
