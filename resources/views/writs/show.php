<p>
    <a href="/@<?= htmlspecialchars($writ->handle) ?>">
        <i class="fa-regular fa-user"></i>
        @<?= htmlspecialchars($writ->handle) ?>
    </a>
</p>
<p>
    <?= \App\Services\MentionService::render(
    $writ->content
) ?>
</p>
<hr>
<?php if (
    \Core\Authentication\Auth::check()
): ?>
    <form
        method="POST"
        action="/bookmarks/<?= htmlspecialchars(
            $writ->public_id
        ) ?>"
    >
        <button type="submit">
            <i class="fa-solid fa-bookmark"></i>
            Save Bookmark
        </button>
    </form>
    <hr>
<?php endif; ?>
<h2>
    <i class="fa-solid fa-comments"></i>
    Discussion
</h2>
<?php require BASE_PATH
    . '/resources/views/comments/form.php'; ?>
<br>
<?php if (empty($comments)): ?>
    <div class="card">
        <p>
            No comments yet.
        </p>
    </div>
<?php else: ?>
    <?php foreach ($comments as $comment): ?>
        <?php require BASE_PATH
            . '/resources/views/comments/item.php'; ?>
        <br>
    <?php endforeach; ?>
<?php endif; ?>
<hr>
<small>
    <i class="fa-solid fa-hashtag"></i>
    <a href="/writs/<?= htmlspecialchars(
        $writ->public_id
    ) ?>">
        <?= htmlspecialchars(
            $writ->public_id
        ) ?>
    </a>
</small>
<?php if (
    \Core\Authentication\Auth::check()
    && \Core\Authentication\Auth::id()
        === (int) $writ->user_id
): ?>
    <hr>
    <div class="writ-actions">
        <a
            href="/writs/<?= htmlspecialchars(
                $writ->public_id
            ) ?>/edit"
            class="icon-button"
            title="Edit writ"
            aria-label="Edit writ"
        >
            <i class="fa-regular fa-pen-to-square"></i>
        </a>
        <form
            method="POST"
            action="/writs/<?= htmlspecialchars(
                $writ->public_id
            ) ?>/delete"
            onsubmit="return confirm(
                'Delete this writ permanently?'
            );"
        >
            <button
                type="submit"
                class="icon-button danger"
                title="Delete writ"
                aria-label="Delete writ"
            >
                <i class="fa-regular fa-trash-can"></i>
            </button>
        </form>
    </div>
<?php endif; ?>