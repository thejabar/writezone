<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>
        <?= htmlspecialchars($title ?? 'WriteZone') ?>
    </title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >
    <link
    rel="stylesheet"
    href="/assets/css/app.css?v=<?= filemtime(BASE_PATH . '/public_html/assets/css/app.css') ?>"
>
</head>
<body>
    <?php require BASE_PATH . '/resources/views/partials/navbar.php'; ?>
    <main class="container">
        <?php if ($message = flash('success')): ?>
            <div class="flash-success">
                <i class="fa-solid fa-circle-check"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <?php require $view; ?>
    </main>
    <?php require BASE_PATH . '/resources/views/partials/footer.php'; ?>
    <button
    id="theme-toggle"
    type="button"
    class="theme-toggle-fab"
    aria-label="Toggle theme"
>
    <i class="fa-solid fa-moon"></i>
</button>
    <script
    defer
    src="/assets/js/theme.js?v=<?= filemtime(
        BASE_PATH . '/public_html/assets/js/theme.js'
    ) ?>"
></script>
</body>
</html>