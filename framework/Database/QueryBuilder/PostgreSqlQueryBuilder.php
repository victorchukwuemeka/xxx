<?php 

namespace Framework\Database\QueryBuilder;

use Framework\Database\QueryBuilder\QueryBuilder;
use Framework\Database\Connection\PostgreSqlConnection;
use Framework\Database\Connection\Connection;


class PostgreSqlQueryBuilder extends  QueryBuilder 
{
    protected  PostgreSqlConnection $connection;
  
    
    public function connection(): Connection 
    {   
        $connection = new Connection;
        return  $connection;
    }
    
    public function __construct(PostgreSqlConnection $connection)
    {
         $this->connection = $connection;
    }
}