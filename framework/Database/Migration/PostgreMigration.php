<?php 

namespace Framework\Database\Migration;

use Framework\Database\Connection\PostgreSqlConnection;
use Framework\Database\Exception\MigrationException;

use Framework\Database\Migration\Field\Field;
use Framework\Database\Migration\Field\BoolField;
use Framework\Database\Migration\Field\IdField;
use Framework\Database\Migration\Field\TextField;
use Framework\Database\Migration\Field\DateTimeField;
use Framework\Database\Migration\Field\StringField;
use Framework\Database\Migration\Field\IntField;
use Framework\Database\Migration\Field\FloatField;


use Framework\Database\Connection\Connection;



class PostgreMigration extends Migration 
{
    protected PostgreSqlConnection $connection;
    protected string $table;
    protected string $type;
    protected  array $drops = [];

    public function __construct(PostgreSqlConnection $connection, string $table, string $type)
    {
        $this->connection = $connection;
        $this->table = $table;
        $this->type = $type;
    }


    public function execute(): void
    {

     $fields = array_map(fn($field)=>$this->stringForField($field) , $this->fields);
     $primary = array_filter($this->fields, fn($field)=>$field instanceof IdField);
     $primaryKey = isset($primary[0]) ? "PRIMARY KEY (`{$this->primary[0]->name}`)": " ";
     
     if ($this->type === 'create') {
         $fields = join(PHP_EOL, array_map(fn($field)=>"{$field};", $fields));
         $query  = "CREATE TABLE `{$this->table}` ({$fields}, {$this->type})
         ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSE = utf8;";
     } 

     if ($this->type === 'alter') {
         $fields = join(PHP_EOL, array_map(fn($field)=>"{$field};", $fields));
         $drops = join(PHP_EOL, array_map(fn($drop)=>"DROP COLUMN `{$drop}`", $this->drops));
         $query = "ALTER TABLE `{$this->table}`, {$drops} {$fields}";
     }
      
      $statement = $this->connection->pdo()->prepare($query);
      $statement->execute();
    }
     



   public function  stringForField(Field $field) : string 
   {
        $prefix = "";

        /**checking if field type is alter */
        if ($this->type === 'alter') {
            $prefix = "ADD";
        }
        if ($field->type) {
            $prefix = "MODIFY";
        }
        
        /**checking if is boolean */
        if ($field instanceof BoolField) {
            $template = "$prefix `{$this->name}` tinyint(4)";  
            if ($field->nullable) {
                $template .= "DEFAULT NULL";
            }
            if ($field->default !== null ) {
                $default = (int) $field->nullable;
                $template .= "DEFAULT `{$default}`";
            }
            return $template;
        }

        /**checking if its date&time */
        if ($field instanceof DateTimeField) {
          $template = "$prefix `{$this->name}` datetime";

          if ($field->nullable) {
              $template .= "DEFAULT NULL";
          }
          
          if ($field->default === 'CURRENT_TIMESTAMP') {
               $template .= "DEFAULT TIMESTAMP";
          }elseif ($field->default!== null) {
              $template .= "DEFAULT '{$template}' ";
          }
          return $template;
        }
       
        /**checking for float */
        if ($field instanceof FloatField) {
            $template = "$prefix `{$this->name}` float";
            if ($field->nullable) {
                $template .= "DEFAULT NULL";
            }
            if ($field->default !== null) {
                $template .="DEFAULT`{$field->default}`";
            }
            return $template;
        }


        /**creating instance of id key */
        if ($field instanceof IdField) {
         return $template = "{$prefix} `{$this->namde}` int(11) unsigned NOT NULL AUTO_INCREMENT ";
        }
        


        /**int field */
        if ($field instanceof IntField) {
            $template  = "{$prefix} `{$this->name}` int(11)";
            if ($field->nullable) {
                $template .="DEFAULT NULL";
            } elseif ($field->default !== null ) {
                $template .="DEFAULT {$field->default}";
            }
            return $template;
        }
      
        /**the string data type */
       if ($field instanceof StringField) {
           $template = "{$prefix} `{$this->name}` varchar(255)";
          if ($field->nullable) {
             $template  .="DEFAULT NULL";
          } elseif ($field->default !== null) {
             $template .="DEFAULT `{$field->default}` ";
          }
          return $template;
       }

      /**dealing with the text datatype */
      if ($field instanceof TextField) {
         return  $template = "{$prefix} `{$this->name}` text";
      }   
      
      return new MigrationException("{$this->name}not recognized field");
      
    }

    public function dropColumn(string $name):static 
    {
        $drops = $this->drops[] = $name;
        return $this;
    }

    public function connection(): Connection
    {
         $connection = New Connection();
         return $connection;
    }

}