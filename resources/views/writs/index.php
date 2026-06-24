<h1>
    <i class="fa-solid fa-pen"></i>
    Latest Writs
</h1>
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
            <?= \App\Services\MentionService::render(
    $writ->content
) ?>
        </div>
        <div class="writ-actions">
            <a href="/writs/<?= htmlspecialchars($writ->public_id) ?>">
                <i class="fa-solid fa-eye"></i>
                View
            </a>
        </div>
    </article>
<?php endforeach; ?>