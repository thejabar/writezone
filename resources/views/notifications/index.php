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
                    <strong>
                        @<?= htmlspecialchars(
                            $notification->handle
                            ?? $notification->username
                        ) ?>
                    </strong>
                    replied to your comment.
                <?php elseif (
                    $notification->type === 'user_followed'
                ): ?>
                    <strong>
                        @<?= htmlspecialchars(
                            $notification->handle
                            ?? $notification->username
                        ) ?>
                    </strong>
                    followed you.
                <?php elseif (
    $notification->type === 'mention_writ'
): ?>
    <strong>
        @<?= htmlspecialchars(
            $notification->handle
            ?? $notification->username
        ) ?>
    </strong>
    mentioned you in a writ.
<?php elseif (
    $notification->type === 'mention_comment'
): ?>
    <strong>
        @<?= htmlspecialchars(
            $notification->handle
            ?? $notification->username
        ) ?>
    </strong>
    mentioned you in a comment.
<?php elseif (
    $notification->type === 'mention_reply'
): ?>
    <strong>
        @<?= htmlspecialchars(
            $notification->handle
            ?? $notification->username
        ) ?>
    </strong>
    mentioned you in a reply.
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