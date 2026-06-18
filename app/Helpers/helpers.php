<?php
declare(strict_types=1);
function view(
    string $view,
    array $data = []
): string {
    extract($data);
    $view = BASE_PATH
        . '/resources/views/'
        . str_replace('.', '/', $view)
        . '.php';
    ob_start();
    require BASE_PATH
        . '/resources/views/layouts/app.php';
    return ob_get_clean();
}