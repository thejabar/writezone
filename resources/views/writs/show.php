<p>
    <a href="/@<?= htmlspecialchars($writ->handle) ?>">
        <i class="fa-regular fa-user"></i>
        @<?= htmlspecialchars($writ->handle) ?>
    </a>
</p>
<p>
    <?= nl2br(
        htmlspecialchars($writ->content)
    ) ?>
</p>
<hr>
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
    <a href="/writs/<?= htmlspecialchars($writ->public_id) ?>">
        <?= htmlspecialchars($writ->public_id) ?>
    </a>
</small>
<?php if (
    \Core\Authentication\Auth::check()
    && \Core\Authentication\Auth::id()
        === (int) $writ->user_id
): ?>
    <hr>
    <p>
        <a href="/writs/<?= htmlspecialchars($writ->public_id) ?>/edit">
            <i class="fa-regular fa-pen-to-square"></i>
            Edit
        </a>
    </p>
    <form
        method="POST"
        action="/writs/<?= htmlspecialchars($writ->public_id) ?>/delete"
    >
        <button type="submit">
            <i class="fa-regular fa-trash-can"></i>
            Delete
        </button>
    </form>
<?php endif; ?>