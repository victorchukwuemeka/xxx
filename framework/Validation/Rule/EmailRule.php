<?php 

namespace Framework\Validation\Rule;

//use Framework\Validation;

use Framework\Validation\Rule\Rule;

class EmailRule implements Rule
{
    public function validate(array $data, string $field, array $params ){
        if (empty($data[$field])) {
            return true;
        }
        if (empty($params[0])) {
            throw InvalidArgumentException('specify a min length');
        }
        $length = (int) $params[0];
        strlen($data[$field]) >= $length;

        return str_contains($data[$field], '@');
    }   

    public function getMessage(array $data, string $field, array $params)
    {
        return " {$field} should be an email";
    }



}