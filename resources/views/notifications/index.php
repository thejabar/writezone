<h1 class="page-title">
    Notifications
</h1>
<?php if (empty($notifications)): ?>
    <div class="card">
        No notifications yet.
    </div>
<?php else: ?>
    <?php foreach (
        $notifications as $notification
    ): ?>
        <article class="card">
            <div class="writ-content">
                <?php if (
                    $notification->type === 'reply_created'
                ): ?>
                    Someone replied to your comment.
                <?php else: ?>
                    Notification received.
                <?php endif; ?>
            </div>
            <div class="writ-meta">
                <?= htmlspecialchars(
                    $notification->created_at
                ) ?>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>