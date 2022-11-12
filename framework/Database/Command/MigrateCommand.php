<?php

namespace Framework\Database\Command;

use Framework\Database\Factory;
use Framework\Database\Connection\MysqlConnection;
use Framework\Database\Connection\SqliteConnection;
use Framework\Database\Connection\PostgreSqlConnection;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command{
    protected static $defaultName = 'migrate';

    protected function configure(){
        $this->setDescription('Migrate the Database')
        ->addOption('fresh', null, InputOption::VALUE_NONE,'Delete all tables before running the migration')
        ->setHelp('this commands look for all migration files and run them');
    }

    protected  function execute(InputInterFace $input, OutputInterface $output){
        $current = getcwd();
        $pattern = 'database/migrations/*.php';
        $paths = glob("{$current}"/"{$pattern}");

        if(count($paths) < 1){
            $this->writeLn('No migrations found');
            return Command::SUCCESS;
        }

        $connection = $factory->connect([
            'type' => 'postgresql',
            'host' => 'localhost',
            'dbname' => 'mydb',
            'username' => 'victor',
            'password' => 'alchemy97',
        ]);
     /*
       * $connection = $factory->Connection([
        *        'type' => 'mysql',
         *       'host' => '127.0.0.1',
          *      'port' => '8080',
           *     'database' => 'php8-mvc',
                'username' => 'root',
                'password' =>  '',
        ]);
         */

        if ($input->getOption('fresh')) {
            $output->writeLn('Delete existing table');
            $connection->dropTables();
            $connection = $this->connection();
        }

        if (!$connection->hasTable('migration')) {
            $output->writeLn('Creating new migration table');
            $this->createMigrationsTables($connection);
        }


        foreach ($paths  as $path) {
            [$prefix, $file] = explode('_', $path);
            [$class, $extension] = explode('.', $file);
            require $path;
            $obj = new $class();
            $obj->migrate($connection);

            $connection->query()->from('migrations')
            ->insert(['name'], ['name' => $class]);

        }
        return Command::SUCCESS;
    }


    public function connection():Connection {
        $factory = new Factory();

        $factory->addConnector('postgresql', function($config){
            return new PostgresqlConnection($config);
        });

        $factory->addConnector('mysql', function($config){
            return new MysqlConnection($config);
        });

        $factory->addConnector('sqlite', function($config){
            return new SqliteConnection($config);
        });
        $config = require getcwd().'/../config/database.php/';

        return $factory->connection($config[$config['default']]);
    }



    private function createMigrationsTables(Connection $connection){
        $table = $connection->createTables('migrations');
        $table->id('id');
        $table->string('name');
        $table->execute();
    }

}
