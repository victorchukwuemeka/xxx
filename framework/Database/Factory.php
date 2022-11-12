<?php

 namespace Framework\Database;

 use Closure;
 use Framework\Database\Connection\Connection;
 use Exception;
 //use Framework\Database\Connection\PostgreSqlConnection;

 //use Framework\Database\Exception\ConnectionException;
 //use Framework\Database\Exception\Exceptions;
 //use Framework\Database\Exception\ErrorMessage\ConMessage;



 class Factory
 {
     protected array $connectors;
     //protected ConnectionException $errorMessage;

     public function addConnector(string $alias, Closure $connector): static
     {
         $this->connectors[$alias] = $connector;
         return $this;
     }

     public function connect(array $config): Connection{
       if (isset($config['default'])) {
         $type = $config['default'];
         $this->connectors[$type]($config);
       }
     }

}

//$factory = new Factory();
//var_dump($factory);
//var_dump($factory->addConnector('pgsql', function($config){
  //  return new PostgreSqlConnection($config);
//}));
//$connection = $factory->connect([
  //  'type' => 'pgsql',
   // 'host' => '127.0.0.1',
   // 'port' => '5432',
   // 'dbname' => 'mydb',
   // 'username' => 'victor',
   // 'password' => 'alchemy97'
  //]);
//var_dump($connection);

//$coco = ['type' => 'pgsql',];
//var_dump($factory->connect($coco));
