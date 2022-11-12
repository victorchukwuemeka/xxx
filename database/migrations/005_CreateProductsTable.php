<?php 

use Framework\Database\Connection\Connection;

class CtreateProductsTable 
{
    public function migrate(Connection $connection){
      $table = $connection->createTable('product');
      $table->id('id');
      $table->string('name');
      $table->text('description');
      $table->execute(); 
    }
}