<?php

declare(strict_types=1);

namespace App\Tests\Repositories\Json;

use App\Exceptions\EntityNotFoundException;
use App\Repositories\Json\JsonDatabasePath;
use PHPUnit\Framework\TestCase;

class JsonDatabasePathTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_ENV['JSON_DATABASE_PATH']);
    }
    public function testJsonDatabasePath(): void
    {
        $expectedPatch = '/var/www/html/expected';
        $_ENV['JSON_DATABASE_PATH'] = $expectedPatch;
        $jsonDatabasePath = new JsonDatabasePath();


        $result = $jsonDatabasePath->jsonDatabasePath;

        $this->assertEquals($result, $expectedPatch);
    }
    public function testJsonDatabasePathException(): void
    {
        $_ENV['JSON_DATABASE_PATH'] = '';

        $this->expectException(EntityNotFoundException::class);

        $jsonDatabasePath = new JsonDatabasePath();
    }
}
