<?php 

namespace App\Console\Commands;

use Symfony\Component\Composer\Command\Command;
use Symfony\Component\Composer\Input\InputArgument;
use Symfony\Component\Composer\Input\InputInterface;
use Symfony\Component\Composer\Output\OutputInterface;

class NameCommand extends Command 
{
    protected static $defaultName = 'name';
    protected $requireName = 'false';

    protected  function configure()
    { 
        $this
        ->setDescription('Print the name in Uppercase')
        ->setHelp("this command take optional and return it in upper case if no name is provided it 
        'stranger' ")
        ->addArgument('name', $this->requireName? InputArgument::REQUIRED :
        InputArgument::OPTIONAL, 'Optional name');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $output->writeIn(strtoupper($input->addArgument('name')?: ('stranger')));
       return Command::SUCCES;
    }


}
