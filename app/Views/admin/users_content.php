<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Users</h1>
            <p class="text-muted mb-0">Manage application users and roles.</p>
        </div>
        <a href="<?= UrlHelper::to('admin/create-user') ?>" class="btn btn-primary">Create User</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>