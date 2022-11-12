<?php 

namespace Framework\Database\Connection;

use Framework\Database\QueryBuilder\MysqlQueryBuilder;
use Framework\Database\Connection\Connection;
use Framework\Database\Migration\MysqlMigration;
use InvalidArgumentException;


use Pdo;

class MysqlConnection implements Connection 
{
    private Pdo $pdo;

    public function __construct(array $config)
    {
        [
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'password' => $password,
            'username' => $username,
        ] = $config;

        if (empty($database) || empty($host) || empty($port)) {
            throw new InvalidArgumentException('it should not be empty');
        }

        $this->pdo = new Pdo(
            "mysql:host = {host}; port = {port}; dbname ={database};", $username, $password
        );


    }

    public function pdo(): Pdo
    {
        return $this->pdo;
    }

    public function query():MysqlQueryBuilder
    {
        return new MysqlQueryBuilder($this);        
    }

    public function alterTable(string $table): MysqlMigration 
    {
        return new MysqlMigration($this, $table, 'alter');
    }


}
