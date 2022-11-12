<?php 

namespace Framework\Database\Migration\Field;

abstract class Field 
{
    protected string $name;
    protected bool $nullable = false;

    public function __construct(string $name)
    {
        return $this->name = $name;
    }

    public function nullable(): static
    {
         $this->nullable = true;
         return $this;
    }


}