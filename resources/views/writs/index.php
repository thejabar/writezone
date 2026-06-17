<!DOCTYPE html>
<html>
<head>
    <title>Writs</title>
</head>
<body>

<h1>Latest Writs</h1>

<?php foreach ($writs as $writ): ?>

    <article>

        <h2>
            <a href="/writs/<?= $writ->id ?>">
                <?= htmlspecialchars(
                    $writ->title ?? 'Untitled'
                ) ?>
            </a>
        </h2>

        <p>
            <?= nl2br(
                htmlspecialchars($writ->content)
            ) ?>
        </p>

        <hr>

    </article>

<?php endforeach; ?>

</body>
</html>