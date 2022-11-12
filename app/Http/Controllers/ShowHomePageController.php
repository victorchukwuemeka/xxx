<?php

namespace App\Http\Controllers;

use Framework\Database\Factory;
use Framework\Database\Connection\PostgreSqlConnection;
use Framework\Database\Connection\MysqlConnection;
use Framework\Database\Connection\SqliteConnection;
use Framework\Routing\Router;

class ShowHomePageController
{

 public function handle(){
    $factory = new Factory();
    //var_dump($factory);

    $config = require __DIR__. '/../../../config/database.php';

    $factory->addConnector('default', function($config){
      return new PostgreSqlConnection($config);
    });


 /*
    //$factory->addConnector('mysql', function($config){
      //return new MysqlConnection($config);
    //});
    //$factory->addConnector('sqlite', function($config){
      // return new SqliteConnection;
    //});


    $default = $config['default'];

    $connection = $factory->connect($default);

        //$connection = $factory->connect([
          //'type' => 'pgsql',
          //'host' => '127.0.0.1',
          //'port' => '5432',
          //'dbname' => 'mydb',
          //'username' => 'victor',
          //'password' => 'alchemy97'
        //]);

//    $products = $connection->query()->select()->from('products')->first();
  //  return view('name',
    //     ['number' => 45,
      //    'featured' => $product
       // ]);*/

       //$bad = "not goo";
       //var_dump($bad);
  }



}

//$show = new ShowHomePageController;
//$show->handle();
//var_dump($show);
