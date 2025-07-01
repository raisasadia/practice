<?php
use yii\helpers\Html;
?>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sessions as $session): ?>
                <tr>
                    <td><?= htmlspecialchars($session['id']) ?></td>
                    <td><?= $session['ipAddress'] ?? '-' ?></td>
                    <td><?= Yii::$app->formatter->asDatetime($session['start'] / 1000) ?></td>
                    <td><?= Yii::$app->formatter->asDatetime($session['lastAccess'] / 1000) ?></td>
                    <td><?= implode(', ', $session['clients'] ?? []) ?></td>
                    <td>
                        <?= Html::beginForm(['site/force-logout-user', 'id' => $user['id']], 'post', ['style' => 'display:inline']) ?>
                            <?= Html::submitButton(
                                'Force Logout',
                                [
                                    'class' => 'btn btn-danger btn-sm',
                                    'data-confirm' => 'Are you sure you want to force logout this session?',
                                ]
                            ) ?>
                        <?= Html::endForm() ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No active sessions found for this user.</p>
<?php endif; ?>

<a href="<?= \yii\helpers\Url::to(['site/user-list']) ?>" class="btn btn-secondary">Back to User List</a>
