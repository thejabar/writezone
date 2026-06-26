<h1>
    <i class="fa-solid fa-magnifying-glass"></i>
    Search
</h1>
<form
    method="GET"
    action="/search"
>
    <input
        type="search"
        name="q"
        value="<?= htmlspecialchars($query) ?>"
        placeholder="Search users, writs and hashtags..."
        autocomplete="off"
    >
    <button type="submit">
        Search
    </button>
</form>
<?php if ($query !== ''): ?>
<hr>
<h2>
    Users
</h2>
<?php if (empty($results['users'])): ?>
    <p>No users found.</p>
<?php else: ?>
    <?php foreach ($results['users'] as $user): ?>
        <article class="card">
            <a href="/@<?= htmlspecialchars($user->handle) ?>">
                <strong>
                    @<?= htmlspecialchars($user->handle) ?>
                </strong>
            </a>
            <br>
            <?= htmlspecialchars(
                $user->username
            ) ?>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
<hr>
<h2>
    Hashtags
</h2>
<?php if (empty($results['hashtags'])): ?>
    <p>No hashtags found.</p>
<?php else: ?>
    <?php foreach ($results['hashtags'] as $tag): ?>
        <article class="card">
            <a href="/hashtags/<?= urlencode($tag) ?>">
                #<?= htmlspecialchars($tag) ?>
            </a>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
<hr>
<h2>
    Writs
</h2>
<?php if (empty($results['writs'])): ?>
    <p>No writs found.</p>
<?php else: ?>
    <?php foreach ($results['writs'] as $writ): ?>
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
<?php endif; ?>