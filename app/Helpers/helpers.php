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
function csrf_token(): string
{
    return \Core\Security\Csrf::token();
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' .
        htmlspecialchars(
            csrf_token(),
            ENT_QUOTES,
            'UTF-8'
        ) .
        '">';
}
