<?php 

namespace Framework\Database\QueryBuilder;

use Framework\Database\Connection\Connection;
use Framework\Database\Exception\QueryException;
use Pdo;
use PdoStatement;

abstract class QueryBuilder 
{  

   protected string $type;
   protected array $columns;
   protected string $table;
   protected int $limit;
   protected int $offset;
   protected array $values;
   protected array $wheres = [];


   /** underlining connection instance  */
   abstract protected function connection():Connection;


    
   /**fetch all  rows matching the query */
   public function all():array {
     $statements = $this->prepare();
     $statements->execute($this->getWhereValues());
     $result = $statements->fetchAll(Pdo::FETCH_ASSOC);

     if (count($result) === 1) {
         return $result[0];
     }

     return null;
   }

   public function getWhereValues():array 
   {
     $values = [];
     if (count($this->wheres) === 0) {
         return $values;
     }
     foreach ($this->wheres as $where) {
         $values[$where[0]] = $where[2];
     }
     return $values;
   }

   public function insert(array $columns , array $values):int {
     $this->type = 'insert';
     $this->columns = $columns;
     $this->values = $values;

     $statement = $this->prepare();
     return $statement->execute($values);
   }

   public function update(array $columns, array $values): int 
   {
     $this->type = 'update';
     $this->columns = $columns;
     $this->values = $values;

     $statement = $this->prepare();
     return $statements->execute($this->getWhereValues() + $values);
   }

   
   public function delete(): int 
   {
       $this->type = 'delete';
       $statement = $this->prepare();
       return $statement->execute($this->getWhereValues());
   }

   
   /**prepare a query against a particular connection */
   public function prepare(): PdoStatement{
       $query = '';
       if ($this->type === 'select') {
           $query = $this->compileSelect($query);
           $query = $this->compileWheres($query);
           $query = $this->compileLimit($query);
       }

       if ($this->type === 'insert') {
           $query = $this->compileInsert($query);
       }
       
       if ($this->type === 'update') {
           $query = $this->compileUpdate($query);
           $query = $this->compileWheres($query);
       }

       if ($this->type === 'delete') {
           $query = $this->complileDelete($query);
           $query = $this->compileWhere($query);
       }
       
       if(empty($query)){
           throw new QueryException('unrecognized type');
       }      
       return $this->connection->pdo()->prepare();
   }
   
   /**add select clause to the query */
   protected  function compileSelect(string $query){
       $query .="SELECT {$this->columns} FROM {$this->table}";
       return $query;
   }
   
   /**add limit and offset clause  */
   protected function compileLimit(string $query): string {
       if($this->limit){
           $query .= "LIMIT {$this->limit}";
       }
       if($this->offset){
          $query .= "OFFSET {$this->offset}";
       }
       return $query;
    }

   /**fetch the row matching the current query */
   public function first():array {
      $statement = $this->take(1)->prepare();
      $statement->execute();
      return $statement->fetchAll(Pdo::FETCH_ASSOC);
   }
   
   /**be able to return a set of query result */
   public function take(int $limit, int $offset = 0):int {
      $this->limit = $limit;
      $this->offset = $offset;
      return $this;
   }

   public function from(string $table): static
   {
      $this->table = $table;
      return $this;
   }
   
   /**indicate query type is a select */
   public function select(string $columns = '*'){
       $this->type = 'select';
       $this->columns = $columns;
       return $this;
   }

   protected  function compileInsert(string $query): string
   {
     $joinedColumns = join(',', $this->columns);
     $joinedPlaceholders = join(',', array_map(fn($column)=>":{$column}", $this->$columns));
     $query .= "INSERT INTO {$this->table} ({$joinedColumns})  VALUE ({$joinedPlaceholders})";
     return $query;
   }
   
   protected function compileWheres(string $query): string 
   {
       if (count($this->wheres) === 0) {
           return $query;
       }
       $query .= "WHERE";
       foreach ($this->wheres as $i => $where) {
           if ($i > 0) {
               $query .= ',';
           }
            [$column ,$comparator, $value] = $where;

            $query .= "{$column} {$comparator}  :{$column}";
       }
       return $query;
   }
   
   protected function compileUpdate(string $query): string 
   {
      $joinedColumns = '';

      foreach ($this->columns  as $i => $column) {
          if ($i > 0) {
              $joinedColumns .= ',';
          }
        $joinedColumns = "{$column} =:{$column}";
      }

      $query .= "UPDATE {$this->table} SET {$joinedColumns}" ;
      return $query;
   }

   protected function compileDelete(string $query): string 
   {
       $query .= "DELETE FROM {$this->table}";
       return $query;
   }
   
   public function getLastInsertId(): string 
   {
      return $this->connection()->pdo()->lastInsertId();
   }
   
   

}