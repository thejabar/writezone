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
</article>