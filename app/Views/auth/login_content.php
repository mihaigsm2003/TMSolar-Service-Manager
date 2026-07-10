<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">Autentificare</h1>
                <p class="text-muted">Accesează panoul TMSolar Service Manager.</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars((string) $error) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= UrlHelper::to('login') ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresă email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Parolă</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Conectare</button>
                </form>
            </div>
        </div>
    </div>
</div>
