<?php 

namespace Framework\Database\Migration\Field;

//use  Framework\Database\Exception\MigrationException; 

use Exception;

class IdField extends Field 
{
    public function default()
    {   
        if ($this->default) {
            throw new Exception('ID field do not have any default value ');
        }
       
    }
}


