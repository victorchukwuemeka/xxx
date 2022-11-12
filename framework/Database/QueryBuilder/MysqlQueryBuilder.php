<?php 

namespace  Framework\Database\QueryBuilder;

use Framework\Database\QueryBuilder\QueryBuilder;
use Framework\Database\Connection\MysqlConnection;

class MysqlQueryBuilder implements QueryBuilder
{   
    protected MysqlConnection $connection;

    public  function __construct(MysqlConnection $connection)
    {
        $this->connection = $connection;
    }
}