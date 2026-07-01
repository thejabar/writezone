<h1 class="page-title">
    Latest Writs
</h1>

<?php if (empty($writs)): ?>

<div class="card">
    No writs published yet.
</div>

<?php else: ?>

<?php foreach ($writs as $candidate): ?>

    <?php $item = $candidate->item; ?>

    <article class="writ-card">

        <div class="writ-header">

            <a
                class="writ-author"
                href="/@<?= htmlspecialchars($item->handle) ?>"
            >
                <i class="fa-solid fa-user"></i>
                @<?= htmlspecialchars($item->handle) ?>
            </a>

            <span class="writ-meta">
                <?= htmlspecialchars($item->created_at) ?>
            </span>

        </div>

        <div class="writ-content">

            <?= nl2br(
                htmlspecialchars($item->content)
            ) ?>

        </div>

        <div class="writ-actions">

            <a href="/writs/<?= htmlspecialchars($item->public_id) ?>">

                <i class="fa-solid fa-eye"></i>

                View

            </a>

        </div>

    </article>

<?php endforeach; ?>

<?php endif; ?>