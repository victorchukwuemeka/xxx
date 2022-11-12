<?php 

namespace Framework\Database\Migration\Field;

use Framework\Database\Migration\Field\Field;

class IntField extends Field 
{
    protected int $default;
    
    public function default(int $value):static 
    {
        $this->default = $value;
        return $this;
    }
}