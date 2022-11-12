<?php 

namespace Framework\Database\Migration\Field;

use Framework\Database\Migration\Field\Field;

class StringField extends Field 
{
    protected string $default;
    public function default(string $value): static 
    {
        $this->default = $value;
        return $this;
    }
}