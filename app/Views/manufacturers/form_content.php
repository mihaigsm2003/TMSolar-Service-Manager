<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3"><?= htmlspecialchars((string) ($pageTitle ?? 'Producător')) ?></h1>
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nume</label>
                            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars((string) ($manufacturer['name'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Persoană Contact</label>
                            <input type="text" class="form-control" name="contact_person" value="<?= htmlspecialchars((string) ($manufacturer['contact_person'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefon</label>
                            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars((string) ($manufacturer['phone'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars((string) ($manufacturer['email'] ?? '')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Site Web</label>
                            <input type="text" class="form-control" name="website" value="<?= htmlspecialchars((string) ($manufacturer['website'] ?? '')) ?>">
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Salvează</button>
                        <a href="<?= UrlHelper::to('manufacturers') ?>" class="btn btn-outline-secondary">Anulează</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
