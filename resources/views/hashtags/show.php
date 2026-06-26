<h1>
    <i class="fa-solid fa-hashtag"></i>
    #<?= htmlspecialchars($tag) ?>
</h1>
<p>
    <?= count($writs) ?>
    writ<?= count($writs) === 1 ? '' : 's' ?>
    found.
</p>
<hr>
<?php if (empty($writs)): ?>
    <div class="card">
        <p>
            No writs found for this hashtag.
        </p>
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
                    <i class="fa-solid fa-user"></i>
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
                    <i class="fa-solid fa-eye"></i>
                    View
                </a>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>