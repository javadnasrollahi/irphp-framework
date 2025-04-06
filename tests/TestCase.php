<?php
namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function get(string $url)
    {
        // Simulate GET request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI']    = $url;

        ob_start();
        require __DIR__ . '/../public/index.php';
        $output = ob_get_clean();

        return $output;
    }

    protected function assertSee(string $content, string $output)
    {
        $this->assertStringContainsString($content, $output);
    }
}
