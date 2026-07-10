<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">Install TMSolar Service Manager</h1>
                <p class="text-muted">This installer creates the database schema, default admin account, and the service management tables for customers, manufacturers, devices, and service orders.</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars((string) $error) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= UrlHelper::to('install.php') ?>">
                    <div class="alert alert-info">The installer will test the database connection, create the tables, and write the configuration file automatically.</div>

                    <div class="mb-3">
                        <label for="db_host" class="form-label">MySQL Host</label>
                        <input type="text" class="form-control" id="db_host" name="db_host" value="<?= htmlspecialchars((string) ($db_host ?? '')) ?>" placeholder="Example: db.example.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="db_name" class="form-label">Database Name</label>
                        <input type="text" class="form-control" id="db_name" name="db_name" value="<?= htmlspecialchars((string) ($db_name ?? '')) ?>" placeholder="Example: my_database" required>
                    </div>

                    <div class="mb-3">
                        <label for="db_user" class="form-label">Username</label>
                        <input type="text" class="form-control" id="db_user" name="db_user" value="<?= htmlspecialchars((string) ($db_user ?? '')) ?>" placeholder="Example: my_user" required>
                    </div>

                    <div class="mb-3">
                        <label for="db_pass" class="form-label">Password</label>
                        <input type="password" class="form-control" id="db_pass" name="db_pass" value="" placeholder="Enter the database password">
                    </div>

                    <button type="submit" class="btn btn-primary">Run installation</button>
                </form>
            </div>
        </div>
    </div>
</div>
