<?php
namespace Tests\Controllers;

use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    public function testIndexViewReturnsHello()
    {
        $output = $this->get('/');

        $this->assertSee('سلام', $output);
        $this->assertSee('به فریم‌ورک شخصی خودت خوش اومدی!', $output);
    }
}
