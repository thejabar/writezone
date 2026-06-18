<!DOCTYPE html>
<html>
<head>
    <title>Writ #<?= $writ->id ?></title>
</head>
<body>

<p>
    <?= nl2br(
        htmlspecialchars($writ->content)
    ) ?>
</p>

<hr>

<small>
    Writ #<?= $writ->id ?>
</small>

</body>
</html>