<?php 

namespace Framework\Database\Connection;

use Framework\Database\QueryBuilder\SqliteQueryBuilder;
use Framework\Database\Connection\Connection;
use InvalidArgumentsException;
use Pdo;

class SqliteConnection implements SqliteQueryBuilder 
{
    private Pdo $pdo;

    public function __construct(array $config)
    {
        ['path' => $path] = $config;
        if (empty($path)) {
            throw new InvalidArgumentException('Incomplete Connection configuration');
        }
        $this->pdo = new Pdo("Sqlite : {$path}");
    }

    public function pdo(): Pdo 
    {
       $this->pdo ;
    }

    public function query(): SqliteQueryBuilder 
    {
       return new SqliteQueryBuilder($this);
    }
}