<?php

declare(strict_types=1);

namespace Core\Database;

class Migrator
{
    public function run(): void
    {
        $files = glob(BASE_PATH . '/database/migrations/*.php');

        sort($files);

        foreach ($files as $file) {
            require $file;
        }

        echo "Migrations completed.\n";
    }
}
