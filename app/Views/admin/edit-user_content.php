<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Editează Utilizator</h1>
            <p class="text-muted mb-0">Actualizează detaliile utilizatorului, rolul și setările de acces.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nume</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars((string) ($user['name'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars((string) ($user['email'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Parolă</label>
                        <input type="password" class="form-control" name="password" placeholder="Lasă gol pentru a păstra parola curentă">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rol</label>
                        <select class="form-select" name="role">
                            <option value="admin" <?= (($user['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Administrator</option>
                            <option value="manager" <?= (($user['role'] ?? '') === 'manager') ? 'selected' : '' ?>>Manager</option>
                            <option value="technician" <?= (($user['role'] ?? '') === 'technician') ? 'selected' : '' ?>>Tehnician</option>
                            <option value="viewer" <?= (($user['role'] ?? '') === 'viewer') ? 'selected' : '' ?>>Vizualizator</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active" <?= (($user['status'] ?? '') === 'active') ? 'selected' : '' ?>>Activ</option>
                            <option value="inactive" <?= (($user['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactiv</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Permisiuni</label>
                        <input type="text" class="form-control" name="permissions" value="<?= htmlspecialchars((string) ($user['permissions'] ?? '')) ?>" placeholder="customers,devices,service_orders">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Salvează Modificările</button>
                    <a href="<?= UrlHelper::to('admin/users') ?>" class="btn btn-outline-secondary">Anulează</a>
                </div>
            </form>
        </div>
    </div>
</div>
