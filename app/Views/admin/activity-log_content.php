<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Jurnal Activitate</h1>
            <p class="text-muted mb-0">Activitatea recentă a sistemului și acțiunile administrative.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Timp</th>
                        <th>Utilizator</th>
                        <th>Acțiune</th>
                        <th>Detalii</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activity as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) $entry['created_at']) ?></td>
                            <td><?= htmlspecialchars((string) ($entry['name'] ?? 'Sistem')) ?></td>
                            <td><?= htmlspecialchars((string) $entry['action']) ?></td>
                            <td><?= htmlspecialchars((string) $entry['details']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>