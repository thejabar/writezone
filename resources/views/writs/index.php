<!DOCTYPE html>
<html>
<head>
    <title>Writs</title>
</head>
<body>

<h1>Latest Writs</h1>

<p>
    <a href="/writs/create">
        Create Writ
    </a>
</p>

<?php foreach ($writs as $writ): ?>

    <article>

        <p>
            <a href="/writs/<?= $writ->id ?>">
                <?= nl2br(
                    htmlspecialchars($writ->content)
                ) ?>
            </a>
        </p>

        <small>
            #<?= $writ->id ?>
        </small>

        <hr>

    </article>

<?php endforeach; ?>

</body>
</html>