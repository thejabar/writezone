<?php
declare(strict_types=1);

namespace Core\Error;

use ErrorException;
use Throwable;

final class ErrorHandler
{
    private static bool $registered = false;

    public static function register(): void
    {
        if (self::$registered) {
            return;
        }

        self::$registered = true;

        set_error_handler(
            static function (
                int $severity,
                string $message,
                string $file,
                int $line
            ): bool {
                if (!(error_reporting() & $severity)) {
                    return false;
                }

                throw new ErrorException(
                    $message,
                    0,
                    $severity,
                    $file,
                    $line
                );
            }
        );

        set_exception_handler(
            static function (Throwable $exception): void {
                self::handleThrowable($exception);
            }
        );

        register_shutdown_function(
            static function (): void {
                self::handleShutdown();
            }
        );
    }

    public static function handleThrowable(Throwable $exception): void
    {
        self::log($exception);

        if (headers_sent()) {
            return;
        }

        $config = self::config();
        $debug = (bool) ($config['debug'] ?? false);

        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');

        if ($debug) {
            echo "Internal Server Error\n\n";
            echo $exception->getMessage();
            return;
        }

        echo 'Internal Server Error.';
    }

    private static function handleShutdown(): void
    {
        $error = error_get_last();

        if (! is_array($error)) {
            return;
        }

        $fatalTypes = [
            E_ERROR,
            E_PARSE,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
        ];

        if (! in_array((int) ($error['type'] ?? 0), $fatalTypes, true)) {
            return;
        }

        $message = (string) ($error['message'] ?? 'Fatal error');
        $file = (string) ($error['file'] ?? 'unknown');
        $line = (int) ($error['line'] ?? 0);

        self::logMessage(
            'Fatal PHP error',
            $message,
            $file,
            $line
        );

        if (headers_sent()) {
            return;
        }

        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Internal Server Error.';
    }

    private static function log(Throwable $exception): void
    {
        self::logMessage(
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }

    private static function logMessage(
        string $type,
        string $message,
        string $file,
        int $line
    ): void {
        $config = self::config();
        $logFile = (string) ($config['log_file'] ?? '');

        if ($logFile === '') {
            return;
        }

        $directory = dirname($logFile);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $requestId = self::requestId();

        $entry = sprintf(
            "[%s] %s [request_id=%s]: %s in %s:%d\n",
            date('c'),
            $type,
            $requestId,
            $message,
            $file,
            $line
        );

        error_log($entry, 3, $logFile);
    }

    private static function requestId(): string
    {
        $value = $_SERVER['HTTP_X_REQUEST_ID'] ?? '';

        if (! is_string($value)) {
            return 'generated';
        }

        $value = preg_replace(
            '/[^A-Za-z0-9._-]/',
            '',
            substr($value, 0, 64)
        );

        if (! is_string($value) || $value === '') {
            return 'generated';
        }

        return $value;
    }

    private static function config(): array
    {
        if (! defined('BASE_PATH')) {
            return [];
        }

        $file = BASE_PATH . '/config/errors.php';

        if (! is_file($file)) {
            return [];
        }

        $config = require $file;

        return is_array($config) ? $config : [];
    }
}
