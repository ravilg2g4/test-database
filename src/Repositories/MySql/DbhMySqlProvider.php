<?php

declare(strict_types=1);

namespace App\Repositories\MySql;

class DbhMySqlProvider
{
    public function getDbhMySql(): \PDO
    {
        $credMySql = new CredMySql();
        $credMySql = $credMySql->credMySql;

        try {
            $dbh = new \PDO(
                "mysql:host={$credMySql['HOSTNAME']};dbname={$credMySql['DATABASE']}",
                $credMySql['USERNAME'],
                $credMySql['PASSWORD']
            );
        } catch (\PDOException $e) {
            die("Ошибка при подключении к MySQL: " . $e->getMessage());
        }
        return $dbh;
    }
}
