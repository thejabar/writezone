<!DOCTYPE html>
<html>
<head>
    <title>@<?= htmlspecialchars($user->handle) ?></title>
</head>
<body>

<h1>
    @<?= htmlspecialchars($user->handle) ?>
</h1>

<?php if (! empty($user->bio)): ?>

    <p>
        <?= nl2br(htmlspecialchars($user->bio)) ?>
    </p>

<?php endif; ?>

<hr>

<h2>Writs</h2>

<?php foreach ($writs as $writ): ?>

    <article>

        <p>
            <a href="/writs/<?= $writ->id ?>">
                <?= nl2br(
                    htmlspecialchars($writ->content)
                ) ?>
            </a>
        </p>

        <hr>

    </article>

<?php endforeach; ?>

</body>
</html>
