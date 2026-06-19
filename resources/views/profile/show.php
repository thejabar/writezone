<h1>
    <i class="fa-solid fa-user"></i>
    @<?= htmlspecialchars(
        $user->handle ?? $user->username
    ) ?>
</h1>
<p>
    <?= htmlspecialchars(
        $user->display_name
        ?? $user->username
    ) ?>
</p>
<?php if (empty($writs)): ?>
    <div class="card">
        <p>
            No writs published yet.
        </p>
    </div>
<?php else: ?>
    <?php foreach ($writs as $writ): ?>
        <article class="writ-card">
            <div class="writ-header">
                <a
                    class="writ-author"
                    href="/@<?= htmlspecialchars($writ->handle) ?>"
                >
                    <i class="fa-solid fa-user"></i>
                    @<?= htmlspecialchars($writ->handle) ?>
                </a>
                <span class="writ-meta">
                    <?= htmlspecialchars($writ->created_at) ?>
                </span>
            </div>
            <div class="writ-content">
                <?= nl2br(
                    htmlspecialchars($writ->content)
                ) ?>
            </div>
            <div class="writ-actions">
                <a href="/writs/<?= $writ->id ?>">
                    <i class="fa-solid fa-eye"></i>
                    View
                </a>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>