<?php 

use Framework\Database\Connection\Connection;

class SeedProducts 
{
    public function migrate(Connection $connection){
        $products = [[
            'name' => 'space shutle',
            'description' => 'this is the main stuff for space travel'
        ],
        [
            'name' => 'space ship',
            'description' => 'second to the other one performing all most same thing'
        ],
        [
            'name' => 'space ',
            'decription' => 'your mother know more about this fucking stuff'
        ]];

        foreach ($products as $product) {
            $connection->query()->from('products')->insert(['name', 'description'], $product);
        }
    }
}