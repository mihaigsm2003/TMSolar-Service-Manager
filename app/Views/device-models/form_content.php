<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3"><?= htmlspecialchars((string) ($pageTitle ?? 'Device Model')) ?></h1>
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Manufacturer</label>
                            <select class="form-select" name="manufacturer_id" required>
                                <option value="">Choose manufacturer</option>
                                <?php foreach ($manufacturers as $manufacturer): ?>
                                    <option value="<?= (int) $manufacturer['id'] ?>" <?= ((int) ($model['manufacturer_id'] ?? 0) === (int) $manufacturer['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string) $manufacturer['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Model Name</label>
                            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars((string) ($model['name'] ?? '')) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars((string) ($model['description'] ?? '')) ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="<?= UrlHelper::to('device-models') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
