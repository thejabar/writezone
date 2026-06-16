<?php

declare(strict_types=1);

use Core\Routing\Router;

return function (Router $router): void {

    $router->get('/', function () {
        return 'Welcome to WriteZone';
    });

};
