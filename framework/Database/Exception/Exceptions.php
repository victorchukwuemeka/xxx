<?php 

namespace Framework\Database\Exception;

//use Framework\Database\Exception\Exceptions;
use Framework\Database\Exception\ErrorMessage\ConMessage;
use Framework\Database\Exception\MigrationException;
use Exception;

abstract class Exceptions
{
  // protected Exception $exception;

  abstract public function errorMessage(): ConMessage;

  //abstract public function migrationExceptions($exception): Exception;

}