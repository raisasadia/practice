<h1>User Details</h1>

<table class="table table-bordered">
    <tr><th>ID</th><td><?= htmlspecialchars($user['id']) ?></td></tr>
    <tr><th>Username</th><td><?= htmlspecialchars($user['username']) ?></td></tr>
    <tr><th>Email</th><td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td></tr>
    <tr><th>First Name</th><td><?= htmlspecialchars($user['firstName'] ?? '-') ?></td></tr>
    <tr><th>Last Name</th><td><?= htmlspecialchars($user['lastName'] ?? '-') ?></td></tr>
    <tr><th>Enabled</th><td><?= $user['enabled'] ? 'Yes' : 'No' ?></td></tr>
</table>

<h2>User Sessions</h2>

<?php if (!empty($sessions)): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Session ID</th>
                <th>IP Address</th>
                <th>Start Time</th>
                <th>Last Access</th>
                <th>Client</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sessions as $session): ?>
                <tr>
                    <td><?= htmlspecialchars($session['id']) ?></td>
                    <td><?= $session['ipAddress'] ?? '-' ?></td>
                    <td><?= date('Y-m-d H:i:s', $session['start']) ?></td>
                    <td><?= date('Y-m-d H:i:s', $session['lastAccess']) ?></td>
                    <td><?= implode(', ', array_keys($session['clients'] ?? [])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No active sessions found for this user.</p>
<?php endif; ?>

<a href="<?= \yii\helpers\Url::to(['site/user-list']) ?>" class="btn btn-secondary">Back to User List</a>
