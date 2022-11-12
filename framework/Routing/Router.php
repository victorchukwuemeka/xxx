<?php
namespace Framework\Routing;
use Excepion;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

use Framework\Routing\Route;

class Router
{
     protected array $routes = [];
     protected array $errorHandler = [];
     protected  Route $current;


     public function add(string $method, string $path,  $handler):Route
     {
         $route = $this->routes[] = new Route($method, $path, $handler);
         return $route;
     }


     public function dispatch()
     {
          $paths = $this->paths();

          $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
          $requestPath = $_SERVER['REQUEST_URI'] ?? '/';

          $matching = $this->match($requestMethod, $requestPath);

         if ($matching) {

            $this->current = $matching;
              try {
                   return $matching->dispatch();
              }
               catch (Throwable $e) {

                   if ($e instanceof ValidationException) {
                    $_SESSION['errors'] = $e->getErrors();
                    return redirect($_SERVER['HTTP_REFERER']);
                   }

                   if (isset($_ENV['APP_NEW']) && $_ENV['APP_NEW'] === 'dev'){
                       $whoops = new Run();
                       $whoops->pushHandler(new prettyPagaHandler());
                       $whoops->register();
                       throw $e;
                   }
                  return $this->dispatchError();
                }
          }


          if (in_array($requestPath, $paths)) {
              return $this->dispatchNotAllowed();
          }
          return $this->dispatchNotFound();
     }

     public function check($arr)
     {
         $result = var_dump($arr);
         return $result;
     }


    private function paths(): array
    {
        $paths =[];

        foreach($this->routes as $route)
        {
            $path = '/';
            $paths[] = $route->path($path);
        }
        return $paths;
    }


    private function match(string $method, string $path): ?Route
    {
       foreach ($this->routes as  $route) {
           if ($route->matches($method, $path)) {
              // return null;
               return $route;
           }
       }
       return null;
    }

    public function  errorHandler(int $code, callable $handler)
    {
       $this->errorHandlers[] = $handler;
    }


   public function dispatchNotAllowed()
   {
       $this->errorHandlers[400] ??= fn()=>"not allowed";
       return $this->errorHandlers[400]();
   }

   public function dispatchNotFound()
   {
       $this->errorHandlers[404] ??= fn()=> "not  found";
       return $this->errorHandlers[404]();
   }

   public function dispatchError()
   {
       $this->errorHandlers[500] ??= fn()=> "server error ";
       return $this->errorHandlers[500]();
   }



   public function redirect($path)
   {
       header(
           "Location : {$path}", $replace = true, $code = 301
       );
       exit;
   }

   public function current(): ?Route
   {
     return $this->current;
   }


   public function route( string $name, array $parameters = [],):string
   { 
       $routeSearch = 'null';
      foreach ($this->routes as $route) {

        if ($route->name() === $name){
           $finds = [];
           $replaces = [];
            foreach ($parameters as $key => $value) {
               // one set for required parameters needed
               array_push($finds, "{{$key}}");
               array_push($replaces, $value);

               // another for optional parameters
               array_push($finds, "{{$key}?}");
               array_push($replaces, $value);
            }
           $path = $route->path();
           $path = str_replace($finds, $replaces, $path);

           // remove any optional parameters not povided
           $path = preg_replace("#{[^}]+}#", '', $path);

           // we should think about warning if the needed parameters
           //are not provided
           return $path;
        }

      }
     //throw new Exception("No route has been found");

     return $routeSearch;
   }

}
