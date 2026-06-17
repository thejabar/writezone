<!DOCTYPE html>
<html>
<head>
    <title>
        <?= htmlspecialchars(
            $writ->title ?? 'Writ'
        ) ?>
    </title>
</head>
<body>

<h1>
    <?= htmlspecialchars(
        $writ->title ?? 'Untitled'
    ) ?>
</h1>

<p>
    <?= nl2br(
        htmlspecialchars($writ->content)
    ) ?>
</p>

</body>
</html>