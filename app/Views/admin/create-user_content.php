<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Creează Utilizator</h1>
            <p class="text-muted mb-0">Adaugă un utilizator nou cu accesul asignat.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nume</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Parolă</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rol</label>
                        <select class="form-select" name="role">
                            <option value="admin">Administrator</option>
                            <option value="manager">Manager</option>
                            <option value="technician">Tehnician</option>
                            <option value="viewer">Vizualizator</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active">Activ</option>
                            <option value="inactive">Inactiv</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Permisiuni</label>
                        <input type="text" class="form-control" name="permissions" placeholder="customers,devices,service_orders">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Creează Utilizator</button>
            </form>
        </div>
    </div>
</div>