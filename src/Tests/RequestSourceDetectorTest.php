<?php

declare(strict_types=1);

namespace App\Tests;

use App\RequestSourceDetector;
use PHPUnit\Framework\TestCase;

class RequestSourceDetectorTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_SERVER['REQUEST_URI']);
    }
    public function testGetRequestSourceForHttp(): void
    {
        $expectedRequestSource = 'http';
        $_SERVER['REQUEST_URI'] = '/request-url';
        $provider = new RequestSourceDetector();

        $result = $provider->getRequestSource();

        $this->assertEquals($expectedRequestSource, $result);
    }
    public function testGetRequestSourceForTerminal(): void
    {
        $expectedRequestSource = 'terminal';
        unset($_SERVER['REQUEST_URI']);
        $provider = new RequestSourceDetector();

        $result = $provider->getRequestSource();

        $this->assertEquals($expectedRequestSource, $result);
    }
}
