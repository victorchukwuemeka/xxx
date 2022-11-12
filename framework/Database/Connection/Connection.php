<?php

namespace Framework\Database\Connection;

use Framework\Database\Migration\Migration;
use Framework\Database\QueryBuilder\Querybuilder;
use Framework\Database\Exception;
use Pdo;

abstract class Connection {    
  
    /** instace of the pdo*/
    abstract public function pdo():Pdo;

    /**new query */
    abstract public  function query(): QueryBuilder;

    /**return list of tables name on this connections */
    abstract public function getTables(): array ;

    /**find out if a table exist on the list */
    abstract public function hasTable(string $name): bool;

    /**drop all tables in the database */
    abstract public function dropTables():int;

    /**creating new migration */
    abstract public function createTable(string $table):Migration;

    /**start a new migration to alter table */
    abstract public function alterTable(string $table): Migration;

}
