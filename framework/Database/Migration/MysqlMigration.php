<?php 

namespace Framework\Database\Migration;

use Framework\Database\Connection\MysqlConnection;
use Framework\Database\Exception\MigrationException;

use Framewor\Database\Migration\Field\Field;
use Framework\Dtatabase\Migration\Field\BoolField;
use Framework\Database\Migration\Field\IdField;
use Framework\Database\Migration\Field\TextField;
use Framework\Database\Migration\Field\DataTimeField;
use Framework\Database\Migration\Field\StringField;
use Framework\Database\Migration\Field\IntField;


class MysqlMigration extends Migration 
{
  protected MysqlConnection $connection;
  protected string $table;
  protected string $type;
  protected array $drops = [];
  

  public function __construct(MysqlConnection $connection, string $table , string $type)
  {
    $this->connection = $connection;
    $this->table = $table;
    $this->type = $type;
  }
   
  public function execute() : void 
  {
      $fields = array_map(fn($field)=>$this->stringForField($field), $this->fields);
      //$fields = join(','.PHP_EOL, $fields);
      $primary = array_filter($this->fields,fn($field)=>$field instanceof IdField);
      $primaryKey = isset($primary[0]) ? "PRIMARY KEY (`{$this->primary[0]->name}`)":'';

      if ($this->type === 'create') {
        $fields = join(PHP_EOL, array_map(fn($field)=>"{$field},", $fields));
        $query = "CREATE TABLÈ{$this->table}` 
        ({$fields},{$primaryKey}) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
      }

      if($this->type === 'alter'){
        $fields = join(PHP_EOL, array_map(fn($field)=>"{$field};", $fields));
        $drops = join(PHP_EOL, array_map(fn($drop)=>"DROP COLUMN `{$drop}`;", $this->drops));
        $query = "ALTER TABLE `{$this->table}` {$fields} {$drops}";
      }

      $statement = $this->connection->pdo()->prepare($query);
      $statement->execute();
  }

  public function stringForField(Field $field): string 
  {  
     $prefix = "";
     
     /**checking if field type is alter */
     if($this->type === 'alter'){
       $prefix = 'ADD';
     }

     if($field->alter){
       $prefix = 'MODIFY';
     }
     

    
    /** checking if its boolean fied type */
    if ($field instanceof BoolField) {
      $template = "{$prefix} `{$field->name}` tinyint(4)";

      if ($field->nullable) {
       $template .= "DEFAULT NULL";
      }

      if ($field->dafault !== null) {
        $default = (int) $field->default;
        $template .= "DEFAULT {$default}";
      }
      return $template;
    }


    /**checking if its data&time datatype field */
    if ($field instanceof DataTimeField ) {
      $template = "{$prefix} `{$field->name}` datetime";
       
      if ($field->nullable) {
         $template .= "DEFAULT NULL";
      }

      if ($field->default === 'CURRENT_TIMESTAMP') {
        $template .= "DEFAULT CURRENT_TIMESTAMP ";
      }elseif ($field->default !== null) {
        $template .= "DEFAULT '{$field->default}'";
      }
      return $template;
    }
 
    /**checking if its float type */
    if($field instanceof FloatField){
      $template = "{$prefix} `{$this->name}` float";
      
      if($field->nullable){
        $template .= "DEFAULT NULL";
      }

      if($field->default !== null){
        $template .= "DEFAULT '{$field->default}'"; 
      } 
      return $template;
    }

    /**when its primary id key */
    if($field instanceof IdField){
      return " {$prefix} `{$field->name}` int(11) unsigned NOT NULL AUTO_INCREMENT ";
    }

    /**int data type */
    if($field instanceof IntField){
      $template = "{$prefix} `{$field->name}` int(11)";

      if($field->nullable){
        $template .= "DEFAULT NULL";
      }
      
      if($field->default !== null){
        $template .= "DEFAULT '{$field->default}'";
      }
      return $template;
    } 

    /**checking if its string data type */
    if($field instanceof StringField){
      $template = "{$prefix} `{$field->name}` varchar(255)";

      if($field->nullable){
        $template .= "DEFAULT NULL";
      }
      if($field->default !== null){
        $template .= "DEFAULT '{$field->default}'"; 
      }
      return $template;

    }
  
    /**checking for character data type */
    if($field instanceof TextField){
      return $template = " {$prefix} `{$field->name}`  text";
    }
    
    throw new MigrationException("{$field->name} not a recognized field name");
  }

  public function dropColumn(string $name):static 
  {
    return $this->drops[] = $name;
  }


}

