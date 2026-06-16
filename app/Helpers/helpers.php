<?php

declare(strict_types=1);

function view(string $path, array $data = []): string
{
    extract($data);

    $path = str_replace('.', '/', $path);

    ob_start();

    require BASE_PATH . '/resources/views/' . $path . '.php';

    return ob_get_clean();
}