<?php

declare(strict_types=1);

namespace App\Tests\Repositories\MySql;

use App\Repositories\MySql\DbhMySqlProvider;
use PDO;
use PHPUnit\Framework\TestCase;

class DbhMySqlProviderTest extends TestCase
{
    public function testGetDbhMySqlInstance(): void
    {
        $dbhProvider = new DbhMySqlProvider();

        $dbh = $dbhProvider->getDbhMySql();

        $this->assertInstanceOf(PDO::class, $dbh);
    }
    public function testGetDbhMySqlConnection(): void
    {
        $dbhProvider = new DbhMySqlProvider();

        $dbh = $dbhProvider->getDbhMySql();

        $this->assertEquals('mysql', $dbh->getAttribute(PDO::ATTR_DRIVER_NAME));
    }
}
