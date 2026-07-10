<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">Instalează TMSolar Service Manager</h1>
                <p class="text-muted">Acest instalator creează schema bazei de date, contul implicit de administrator și tabelele de management pentru clienți, producători, dispozitive și comenzi service.</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars((string) $error) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= UrlHelper::to('install.php') ?>">
                    <div class="alert alert-info">Instalatorul va testa conexiunea la baza de date, va crea tabelele și va scrie automat fișierul de configurare.</div>

                    <div class="mb-3">
                        <label for="db_host" class="form-label">Host MySQL</label>
                        <input type="text" class="form-control" id="db_host" name="db_host" value="<?= htmlspecialchars((string) ($db_host ?? '')) ?>" placeholder="Exemplu: db.exemplu.ro" required>
                    </div>

                    <div class="mb-3">
                        <label for="db_name" class="form-label">Nume Bază de Date</label>
                        <input type="text" class="form-control" id="db_name" name="db_name" value="<?= htmlspecialchars((string) ($db_name ?? '')) ?>" placeholder="Exemplu: baza_mea" required>
                    </div>

                    <div class="mb-3">
                        <label for="db_user" class="form-label">Utilizator</label>
                        <input type="text" class="form-control" id="db_user" name="db_user" value="<?= htmlspecialchars((string) ($db_user ?? '')) ?>" placeholder="Exemplu: utilizator_meu" required>
                    </div>

                    <div class="mb-3">
                        <label for="db_pass" class="form-label">Parolă</label>
                        <input type="password" class="form-control" id="db_pass" name="db_pass" value="" placeholder="Introdu parola bazei de date">
                    </div>

                    <button type="submit" class="btn btn-primary">Rulează instalarea</button>
                </form>
            </div>
        </div>
    </div>
</div>
