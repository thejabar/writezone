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
        <?= \App\Services\MentionService::render(
    $comment->content
) ?>
    </div>
    <?php if (\Core\Authentication\Auth::check()): ?>
        <?php
        $score = \App\Models\CommentVote::score(
            (int) $comment->id
        );
        $userVote = \App\Models\CommentVote::userVote(
            (int) $comment->id,
            (int) \Core\Authentication\Auth::id()
        );
        ?>
    <?php endif; ?>
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
              <?= \App\Services\MentionService::render(
    $reply->content
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
            <form
                method="POST"
                action="/comments/<?= $comment->id ?>/upvote"
            >
                <button
                    type="submit"
                    class="icon-button <?= $userVote === 1
                        ? 'active'
                        : '' ?>"
                    title="Upvote"
                    aria-label="Upvote"
                >
                    <i class="fa-solid fa-thumbs-up"></i>
                </button>
            </form>
            <span class="vote-count">
                <?= $score ?>
            </span>
            <form
                method="POST"
                action="/comments/<?= $comment->id ?>/downvote"
            >
                <button
                    type="submit"
                    class="icon-button downvote <?= $userVote === -1
                        ? 'active'
                        : '' ?>"
                    title="Downvote"
                    aria-label="Downvote"
                >
                    <i class="fa-solid fa-thumbs-down"></i>
                </button>
            </form>
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
    <?php if (
        \Core\Authentication\Auth::check()
    ): ?>
        <div
            class="reply-form"
            style="display: none; margin-top: 1rem;"
        >
            <form
                method="POST"
                action="/comments/<?= $comment->id ?>/reply"
            >
                <textarea
                    name="content"
                    placeholder="Write a reply..."
                    required
                ></textarea>
                <br><br>
                <button type="submit">
                    <i class="fa-solid fa-paper-plane"></i>
                    Reply
                </button>
            </form>
        </div>
    <?php endif; ?>
</article>