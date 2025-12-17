<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;

class ServeWithSqliteCommand extends BaseServeCommand
{
    /**
     * Build the server command array.
     *
     * @return array<int, string>
     */
    protected function serverCommand()
    {
        $command = parent::serverCommand();

        $extensions = $this->sqliteExtensionArgs();
        if (! empty($extensions)) {
            array_splice($command, 1, 0, $extensions);
        }

        return $command;
    }

    /**
     * Build the -d extension arguments for the bundled SQLite drivers.
     *
     * @return array<int, string>
     */
    protected function sqliteExtensionArgs(): array
    {
        $paths = [
            base_path('vendor/php-extensions/usr/lib/php/20230831/pdo_sqlite.so'),
            base_path('vendor/php-extensions/usr/lib/php/20230831/sqlite3.so'),
            base_path('../vendor/php-extensions/usr/lib/php/20230831/pdo_sqlite.so'),
            base_path('../vendor/php-extensions/usr/lib/php/20230831/sqlite3.so'),
        ];

        $args = [];

        foreach ($paths as $path) {
            if (is_file($path)) {
                $args[] = '-d';
                $args[] = 'extension='.$path;
            }
        }

        return $args;
    }
}
