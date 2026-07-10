<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5 text-center">
                <h1 class="h3 mb-3">Cererea a fost trimisă</h1>
                <p class="text-muted mb-3">Mulțumim! Cererea ta online a fost înregistrată cu succes.</p>
                <?php if (!empty($orderNumber)): ?>
                    <div class="alert alert-success mb-3">
                        Număr comandă: <strong><?= htmlspecialchars((string) $orderNumber) ?></strong>
                    </div>
                <?php endif; ?>
                <?php if (!empty($mailWarning)): ?>
                    <div class="alert alert-warning mb-3">
                        Comanda este înregistrată, dar cel puțin o notificare email (client sau administrator) nu a putut fi trimisă.
                    </div>
                <?php endif; ?>
                <p class="mb-4">Vei primi confirmarea pe email imediat ce cererea este procesată.</p>
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="<?= UrlHelper::to('comanda-online') ?>" class="btn btn-primary">Trimite altă comandă</a>
                    <a href="<?= UrlHelper::to('dashboard') ?>" class="btn btn-outline-secondary">Panou principal</a>
                </div>
            </div>
        </div>
    </div>
</div>
