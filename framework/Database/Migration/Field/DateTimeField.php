<?php 

namespace Framework\Database\Migration\Field;

use Framework\Database\Migration\Field\Field;

class DateTimeField extends Field 
{
    protected mixed $default;

    public function default(mixed $value): static 
    {
        $this->default = $value ;
        return $this;
    }
}


