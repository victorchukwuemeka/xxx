<?php 

return [
 //'default' => env('DB_CONNECTION', 'pgsql'),
 
 'default' =>[
      
    'pgsql' =>[
        'type' => 'pgsql',
        //'host' => getenv( 'DB_HOST', '127.0.0.1'),
        //'port' => getenv( 'DB_PORT', '5432'),
        //'database' =>getenv('DB_DATABASE','mydb'),
        //'username' =>getenv('DB_USERNAME', 'victor'),
        //'password' => getenv('alchemy97')
        'host' => '127.0.0.1',
        'port' => '5432',
        'database' => 'mydb',
        'username'  => 'victor',
        'password' => 'alchemy97'
    ],
  ],
  
    'mysql' =>[
        'type' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '8080',
        'database' => 'php8-mvc',
        'username' => 'port',
        'password' => '',
    ],

    'sqlite' => [
        'type' => 'sqlite',
        'path' => __DIR__ .'/../database/database.sqlite',
    ],

    
];
