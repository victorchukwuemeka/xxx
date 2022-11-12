<?php 

namespace Framework\Database\Migration\Field;


use Framework\Database\Migration\Field\Field;

class FloatField extends Field 
{
    protected float $default;

    public function default(float $value): static 
    {   
        $this->default = $value;
        return $this;
    }
}