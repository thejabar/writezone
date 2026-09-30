<?php
declare(strict_types=1);

namespace App\Middleware;

use Core\Http\Request;
use Core\Http\Response;

final class RequestBoundaryMiddleware
{
    public function handle(Request $request, callable $next): mixed
    {
        $config = require BASE_PATH . '/config/security.php';
        $limits = $config['request'] ?? [];
        $contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
        $maxBytes = (int) ($limits['max_bytes'] ?? 1048576);
        if ($contentLength > $maxBytes) {
            return Response::send('Request entity too large.', 413);
        }
        $maxVars = (int) ($limits['max_input_vars'] ?? 100);
        if ($this->countVariables($_GET) + $this->countVariables($_POST) > $maxVars) {
            return Response::send('Too many input variables.', 400);
        }
        if ($this->containsArray($_GET) || $this->containsArray($_POST)) {
            return Response::send('Array input is not allowed.', 400);
        }
        $maxDepth = (int) ($limits['max_input_depth'] ?? 10);
        if ($this->maxDepth($_GET) > $maxDepth || $this->maxDepth($_POST) > $maxDepth) {
            return Response::send('Input nesting depth exceeded.', 400);
        }
        $maxScalarLength = (int) ($limits['max_scalar_length'] ?? 10000);
        if ($this->hasOversizedScalar($_GET, $maxScalarLength) || $this->hasOversizedScalar($_POST, $maxScalarLength)) {
            return Response::send('Input value too long.', 400);
        }
        return $next($request);
    }

    private function containsArray(mixed $value): bool
    {
        if (! is_array($value)) {
            return false;
        }
        foreach ($value as $child) {
            if (is_array($child)) {
                return true;
            }
        }
        return false;
    }


    private function countVariables(mixed $value): int
    {
        if (! is_array($value)) {
            return 1;
        }
        $count = 0;
        foreach ($value as $child) {
            $count += $this->countVariables($child);
        }
        return $count;
    }

    private function maxDepth(mixed $value, int $depth = 0): int
    {
        if (! is_array($value)) {
            return $depth;
        }
        $max = $depth;
        foreach ($value as $child) {
            $max = max($max, $this->maxDepth($child, $depth + 1));
        }
        return $max;
    }

    private function hasOversizedScalar(mixed $value, int $maxLength): bool
    {
        if (is_array($value)) {
            foreach ($value as $child) {
                if ($this->hasOversizedScalar($child, $maxLength)) {
                    return true;
                }
            }
            return false;
        }
        return is_string($value) && strlen($value) > $maxLength;
    }
}
