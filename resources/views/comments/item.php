<article class="card">
    <div class="writ-header">
        <a
            class="writ-author"
            href="/@<?= htmlspecialchars($comment->handle) ?>"
        >
            <i class="fa-solid fa-user"></i>
            @<?= htmlspecialchars($comment->handle) ?>
        </a>
        <span class="writ-meta">
            <?= htmlspecialchars($comment->created_at) ?>
        </span>
    </div>
    <div class="writ-content">
        <?= nl2br(
            htmlspecialchars($comment->content)
        ) ?>
    </div>
    <?php foreach (
        \App\Models\Comment::replies(
            (int) $comment->id
        ) as $reply
    ): ?>
        <div class="comment-reply">
            <div class="writ-header">
                <strong>
                    @<?= htmlspecialchars(
                        $reply->handle
                    ) ?>
                </strong>
                <span class="writ-meta">
                    <?= htmlspecialchars(
                        $reply->created_at
                    ) ?>
                </span>
            </div>
            <div class="writ-content">
                <?= nl2br(
                    htmlspecialchars(
                        $reply->content
                    )
                ) ?>
            </div>
            <?php if (
                \Core\Authentication\Auth::check()
                && \Core\Authentication\Auth::id()
                    === (int) $reply->user_id
            ): ?>
                <div class="comment-actions">
                    <a
                        href="/comments/<?= $reply->id ?>/edit"
                        class="icon-button"
                        title="Edit reply"
                        aria-label="Edit reply"
                    >
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    <form
                        method="POST"
                        action="/comments/<?= $reply->id ?>/delete"
                        onsubmit="return confirm(
                            'Delete this reply permanently?'
                        );"
                    >
                        <button
                            type="submit"
                            class="icon-button danger"
                            title="Delete reply"
                            aria-label="Delete reply"
                        >
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <div class="comment-actions">
        <?php if (
            \Core\Authentication\Auth::check()
        ): ?>
            <button
                type="button"
                class="icon-button reply-toggle"
                title="Reply"
                aria-label="Reply"
            >
                <i class="fa-solid fa-reply"></i>
            </button>
        <?php endif; ?>
        <?php if (
            \Core\Authentication\Auth::check()
            && \Core\Authentication\Auth::id()
                === (int) $comment->user_id
        ): ?>
            <a
                href="/comments/<?= $comment->id ?>/edit"
                class="icon-button"
                title="Edit comment"
                aria-label="Edit comment"
            >
                <i class="fa-regular fa-pen-to-square"></i>
            </a>
            <form
                method="POST"
                action="/comments/<?= $comment->id ?>/delete"
                onsubmit="return confirm(
                    'Delete this comment permanently?'
                );"
            >
                <button
                    type="submit"
                    class="icon-button danger"
                    title="Delete comment"
                    aria-label="Delete comment"
                >
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </form>
        <?php endif; ?>
    </div>
</article>