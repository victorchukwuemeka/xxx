<?php 

namespace Framework\Database\Connection;

use Framework\Database\QueryBuilder\PostgreSqlQueryBuilder;
use Framework\Database\Connection\Connection;
use Framework\Database\Migration\Migration;
use Framework\Database\Migration\PostgreMigration;
use InvalidArgumentException;
use Pdo;

class PostgreSqlConnection  extends Connection
{
   private \PDO  $pdo;

   public function __construct(array $config)
   {
      /*
      [
        'host' =>$localhost,
        'dbname' => $dbname,
        'username' => $username,
        'password' => $password
      ] = $config;
      */

      /*if (empty($localhost) || empty($dbname) || empty($username) || empty($password)) {
          throw new InvalidArgumentException("it should not be empty");
          
      }*/
      
      //$this->pdo = new Pdo('pgsql:host=$host; dbname = $dbname','$username', '$password');
        
      $dsn = $config['dsn'] ?? '';
      $user = $config['user'] ?? '';
      $password = $config['password'] ?? '';
      
      $this->pdo = new PDO($dsn, $user, $password);
      $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
   }

   public function pdo(): pdo
   {
      return $this->pdo;
   }

   public function Query():PostgreSqlQueryBuilder
   {
     return new  PostgreSqlQueryBuilder($this);
   }

   public function alterTable(string $table): PostgreMigration
   {
      return new PostgreMigration($this, $table, 'alter');
   }

   public function getTables(): array
   {
       return $this->getTables();
   }

   public function dropTables(): int
   {  
      $num = 0;
      return $num;
   }

   public function createTable(string $table): Migration
   {
      $migration = new Migration($table);
      return $migration;
   }

   public function hasTable($name): bool
   {  
      $value = false;
      if ($name) {
         $value = TRUE;
      }
     
      return $value;
   }

}

$config = [

];
$pgsql = new PostgreSqlConnection($config);