<h1>
    <i class="fa-solid fa-bookmark"></i>
    My Bookmarks
</h1>
<?php if (empty($writs)): ?>
<div class="card">
    No bookmarks yet.
</div>
<?php else: ?>
<?php foreach ($writs as $writ): ?>
    <article class="writ-card">
        <div class="writ-header">
            <a
                class="writ-author"
                href="/@<?= htmlspecialchars(
                    $writ->handle
                ) ?>"
            >
                @<?= htmlspecialchars(
                    $writ->handle
                ) ?>
            </a>
            <span class="writ-meta">
                <?= htmlspecialchars(
                    $writ->created_at
                ) ?>
            </span>
        </div>
        <div class="writ-content">
            <?= \App\Services\MentionService::render(
    $writ->content
) ?>
        </div>
        <div class="writ-actions">
            <a href="/writs/<?= htmlspecialchars(
                $writ->public_id
            ) ?>">
                View
            </a>
        </div>
    </article>
<?php endforeach; ?>
<?php endif; ?>