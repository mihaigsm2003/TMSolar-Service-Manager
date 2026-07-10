<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3"><?= htmlspecialchars((string) ($pageTitle ?? 'Client')) ?></h1>
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prenume</label>
                            <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars((string) ($customer['first_name'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nume</label>
                            <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars((string) ($customer['last_name'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefon</label>
                            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars((string) ($customer['phone'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars((string) ($customer['email'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Companie</label>
                            <input type="text" class="form-control" name="company" value="<?= htmlspecialchars((string) ($customer['company'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">VAT</label>
                            <input type="text" class="form-control" name="vat_number" value="<?= htmlspecialchars((string) ($customer['vat_number'] ?? '')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adresă</label>
                            <textarea class="form-control" name="address" rows="3"><?= htmlspecialchars((string) ($customer['address'] ?? '')) ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Salvează</button>
                        <a href="<?= UrlHelper::to('customers') ?>" class="btn btn-outline-secondary">Anulează</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
