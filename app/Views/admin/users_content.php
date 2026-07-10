<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Utilizatori</h1>
            <p class="text-muted mb-0">Gestionează utilizatorii aplicației și rolurile acestora.</p>
        </div>
        <a href="<?= UrlHelper::to('admin/create-user') ?>" class="btn btn-primary">Creează Utilizator</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nume</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Status</th>
                        <th>Creat la</th>
                        <th class="text-end">Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) $user['name']) ?></td>
                            <td><?= htmlspecialchars((string) $user['email']) ?></td>
                            <td><?= htmlspecialchars((string) $user['role']) ?></td>
                            <td><?= htmlspecialchars((string) $user['status']) ?></td>
                            <td><?= htmlspecialchars((string) $user['created_at']) ?></td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="<?= UrlHelper::to('admin/edit-user/' . (int) $user['id']) ?>" class="btn btn-sm btn-outline-primary">Editează</a>
                                    <a href="<?= UrlHelper::to('admin/delete-user/' . (int) $user['id']) ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Sigur dorești să ștergi acest utilizator?');">Șterge</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>