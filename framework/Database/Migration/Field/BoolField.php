<?php 

namespace Framework\Database\Migration\Field;

use Framework\Database\Migration\Field\Field;

class BoolField extends Field
{
    public bool $default;

    public function default(bool $value): static
    {
        $this->default = $value;
        return $this;
    }
}