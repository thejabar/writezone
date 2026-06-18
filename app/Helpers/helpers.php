<?php

declare(strict_types=1);

function view(string $path, array $data = []): string
{
    extract($data);

    $path = str_replace('.', '/', $path);

    ob_start();

    require BASE_PATH . '/resources/views/partials/header.php';

    require BASE_PATH . '/resources/views/' . $path . '.php';

    require BASE_PATH . '/resources/views/partials/footer.php';

    return ob_get_clean();
}