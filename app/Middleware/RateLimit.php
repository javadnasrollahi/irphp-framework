<?php
namespace App\Middleware;

class RateLimit
{
    /**
     * $router->get('/api', 'X@y', ['RateLimit:60,60']); // 60 requests per 60s
     */
    public function handle($maxAttempts = null, $decaySeconds = null)
    {
        $maxAttempts  = (int) ($maxAttempts ?? env('RATE_LIMIT_MAX', 60));
        $decaySeconds = (int) ($decaySeconds ?? env('RATE_LIMIT_WINDOW', 60));

        $dir = storage_path('ratelimit');
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $key  = $this->resolveKey();
        $file = $dir . '/' . md5($key) . '.json';

        $handle = fopen($file, 'c+');
        flock($handle, LOCK_EX);

        $raw   = stream_get_contents($handle);
        $state = $raw ? json_decode($raw, true) : null;
        $now   = time();

        if (! $state || $now >= $state['reset_at']) {
            $state = ['count' => 0, 'reset_at' => $now + $decaySeconds];
        }

        $state['count']++;

        $remaining = max(0, $maxAttempts - $state['count']);

        if (! headers_sent()) {
            header('X-RateLimit-Limit: ' . $maxAttempts);
            header('X-RateLimit-Remaining: ' . $remaining);
        }

        if ($state['count'] > $maxAttempts) {
            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($state));
            flock($handle, LOCK_UN);
            fclose($handle);

            http_response_code(429);
            header('Retry-After: ' . ($state['reset_at'] - $now));
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Too Many Requests']);
            exit;
        }

        ftruncate($handle, 0);
        rewind($handle);
        fwrite($handle, json_encode($state));
        flock($handle, LOCK_UN);
        fclose($handle);
    }

    protected function resolveKey(): string
    {
        return ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . '|' . ($_SERVER['REQUEST_URI'] ?? '');
    }
}
