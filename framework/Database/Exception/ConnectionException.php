<?php 

namespace Framework\Database\Exception;

use Framework\Database\Exception\Exceptions;
use Framework\Database\Exception\ErrorMessage\ConMessage;

class ConnectionException extends  Exceptions  
{   
   protected  ConMessage $errorCon;
   
   public function __construct(ConMessage $errorCon)
   {
     $this->errorCon = $errorCon;
   }

   public function handle(Exceptions $erroCon): Exceptions 
   {
       $erroCon = $this->erroMessage();
       return $erroCon;
   }

   public function errorMessage(): ConMessage
   {
     return new ConMessage($this);
   }
}

