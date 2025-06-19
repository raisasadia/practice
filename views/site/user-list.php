<h1>Keycloak Users</h1>

<table cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Email</th>
            <th>First Name</th>
            <th>Last Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $index => $user): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($user['username'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($user['email'] ?? 'no email') ?></td>
                <td><?= htmlspecialchars($user['firstName'] ?? '-') ?></td>
                <td><?= htmlspecialchars($user['lastName'] ?? '-') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
