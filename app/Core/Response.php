<?php
namespace App\Core;

class Response
{
    protected int $statusCode     = 200;
    protected array $headers      = [];
    protected string $content     = '';
    protected string $contentType = 'text/html';

    protected ?string $fileToDownload = null;

    public const HTTP_OK             = 200;
    public const HTTP_CREATED        = 201;
    public const HTTP_NO_CONTENT     = 204;
    public const HTTP_BAD_REQUEST    = 400;
    public const HTTP_UNAUTHORIZED   = 401;
    public const HTTP_FORBIDDEN      = 403;
    public const HTTP_NOT_FOUND      = 404;
    public const HTTP_INTERNAL_ERROR = 500;

    public static function make(): self
    {
        return new self();
    }
    public function status(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }
    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /*
    return Response::make()
            ->json(['message' => 'Hello API'])
            ->status(200)
            ->header('X-App-Version', '1.0')
            ->send();
    */
    public function json(array $data): self
    {
        $this->content     = json_encode($data);
        $this->contentType = 'application/json';
        return $this;
    }

    /*
    return Response::make()
            ->status(Response::HTTP_OK)
            ->view('index', ['name' => 'Amir'])
            ->send();
    */
    public function view(string $view, array $data = []): self
    {
        $this->content     = View::make($view, $data);
        $this->contentType = 'text/html';
        return $this;
    }

    public function text(string $text): self
    {
        $this->content     = $text;
        $this->contentType = 'text/plain';
        return $this;
    }

    /*
    return Response::make()->download('path/to/file.txt')->send();
    return Response::make()
        ->download(__DIR__ . '/../../storage/files/resume.pdf', 'my-resume.pdf')
        ->send();
    */
    public function download(string $filePath, ?string $fileName = null): self
    {
        if (! file_exists($filePath)) {
            $this->statusCode  = self::HTTP_NOT_FOUND;
            $this->contentType = 'text/plain';
            $this->content     = 'File not found.';
            return $this;
        }

        $fileName = $fileName ?? basename($filePath);

        $this->statusCode = self::HTTP_OK;
        $this->headers    = array_merge($this->headers, [
            'Content-Description' => 'File Transfer',
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length'      => filesize($filePath),
        ]);

                                    // بجای ذخیره محتوا در $this->content، می‌گیم محتوا در send() مستقیماً stream شه
        $this->content        = ''; // از قبل هیچی نشون نده
        $this->fileToDownload = $filePath;

        return $this;
    }

    //Response::make()->redirect('/login');
    public function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header("Location: $url");
        exit;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        if ($this->fileToDownload) {
            readfile($this->fileToDownload);
            exit;
        }

        header("Content-Type: {$this->contentType}");
        echo $this->content;
    }

}
