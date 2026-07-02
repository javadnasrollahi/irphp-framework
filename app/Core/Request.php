<?php
namespace App\Core;

class Request
{
    protected array $query;
    protected array $body;
    protected array $server;
    protected array $files;
    protected ?array $json = null;

    public function __construct(array $query = null, array $body = null, array $server = null, array $files = null)
    {
        $this->query  = $query ?? $_GET;
        $this->body   = $body ?? $_POST;
        $this->server = $server ?? $_SERVER;
        $this->files  = $files ?? $_FILES;
    }

    public static function capture(): self
    {
        return new self();
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(): string
    {
        return parse_url($this->server['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    }

    public function isJson(): bool
    {
        $contentType = $this->server['CONTENT_TYPE'] ?? $this->server['HTTP_CONTENT_TYPE'] ?? '';
        return str_contains($contentType, 'application/json');
    }

    protected function jsonBody(): array
    {
        if ($this->json === null) {
            $raw        = file_get_contents('php://input');
            $this->json = json_decode($raw, true) ?: [];
        }
        return $this->json;
    }

    public function all(): array
    {
        if ($this->isJson()) {
            return array_merge($this->query, $this->jsonBody());
        }
        return array_merge($this->query, $this->body);
    }

    public function input(string $key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    public function only(array $keys): array
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }

    public function except(array $keys): array
    {
        return array_diff_key($this->all(), array_flip($keys));
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    public function header(string $key, $default = null)
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $this->server[$key] ?? $default;
    }

    public function bearerToken(): ?string
    {
        $header = $this->header('Authorization', '');
        return str_starts_with($header, 'Bearer ') ? substr($header, 7) : null;
    }

    public function file(string $key)
    {
        return $this->files[$key] ?? null;
    }

    public function ip(): ?string
    {
        return $this->server['REMOTE_ADDR'] ?? null;
    }
}
