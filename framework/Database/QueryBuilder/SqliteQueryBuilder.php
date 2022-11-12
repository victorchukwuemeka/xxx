<?php 

namespace Framework\Database\QueryBuilder;

use Framework\Database\Connection\SqliteConnection;
use Framework\Database\QueryBuilder\QueryBuilder;

class SqliteQueryBuilder implements QueryBuilder
{
    protected SqliteConnection $connection;

    public function __construct(SqliteQueryBuilder $connection)
    {
        $this->connection = $connection;
    }
}