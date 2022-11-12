<?php 

namespace Framework\Database\Exception; 

use Exception;

class MigrationException extends Exception
{   
    protected ?string  $name;
    protected ?string  $field;

    public function __construct(string $exceptionName)
    {
        $this->name  = $exceptionName;
        $this->field = $this->name;

        if ($this->name) {
            echo "Message : " . $this->errorMessage($this->name);
        }

    }

    public function errorMessage($field)
    {
        $errorMessage = "this field is not compactible";
        return $errorMessage;
    }
}
