<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Activity Log</h1>
            <p class="text-muted mb-0">Recent system activity and administrative actions.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activity as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) $entry['created_at']) ?></td>
                            <td><?= htmlspecialchars((string) ($entry['name'] ?? 'System')) ?></td>
                            <td><?= htmlspecialchars((string) $entry['action']) ?></td>
                            <td><?= htmlspecialchars((string) $entry['details']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>