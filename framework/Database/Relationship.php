<?php 

namespace Framework\Database;

use Framework\Database\ModelCollector;

class Relatioship 
{
   public  ModelCollector $collector;
   public  string $method;

   public function __construct(string $method, ModelCollector $collector )
   {
       $this->collector = $collector;
       $this->method = $method;
   }

   public function __invoke(array $parameters =[]):mixed 
   {
       return $this->collector->method(... $parameters);
   }

   public function __call(string $method, array $parameters = []):mixed
   {
       return $this->collector->method(... $parameters);
   }


}
