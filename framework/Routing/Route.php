<?php 

namespace Framework\Routing;

class Route 
{
    protected string $method;
    protected string $path;
    protected  $handler;
    protected array $parameters = [];
    protected ?string $name = null;
   // array  $found = [];


    public function __construct(string $method, string $path, $handler)
    {
       $this->method = $method;
       $this->path = $path;
       $this->handler = $handler;
    }
    

    public function method(string $method): string
    {
        return  $this->method;
    }

    public function path(string $path): string 
    {
        return $this->path;
    }
    
    public function matches(string $method, string $path): bool 
    {
        if($this->method === $method && $this->path === $path)
        {
          return true;
        }
        $parameterNames = [];
        
        /** the normalize method is for making sure there is a '/' covering 
         * every name in the uri eg 'home' is the '/home/'
         */
        $pattern = $this->normalisePath($this->path);

        /**
         * this property is for converting name parameters into 
         * regular expression  egs are 
         * '/home/' remains '/home/' 
         * '/product/{id}/' becomes '/product/([^/]+)
         * '/blog/{slug?}/' becomes '/blog/([^/]*)(?:/?)' expression 
         * 
         */
         $pattern = preg_replace_callback('#{[^}]+}/#',
             function (array $found) use (&$parameterNames)
             {
               array_push($parameterNames, rtrim($found[1], '?'));
         
              /**
              * if an option is left we make the following 
              * splash optional as well 
              */ 
              if (str_ends_with($found[1], '?')) {
                return '([^/]*)(?:/?)';
              }
              return '([^/]+)/';
             },
            $pattern,
         );
            
 
         /**
         * if there are no route parameters, and it
         * and it not a literal match 
         * the request will never match
         * 
         */
        if (!str_contains($pattern , '+') && !str_contains($pattern , '*')) {
          return false;
        }
        preg_match_all("#{$pattern}#", $this->normalisePath($path), $matches);
        $parameterValues = [];
        if (count($matches[1])> 0) {
           /**
            * if the route matches the request path 
            * we can assemble the parameters
            * we can return true for the parameters
            */
             foreach ($matches[1] as $value) {
               array_push($parameterValues, $value);
             }
            
             /**
              * make an empty array so that we can still
              * call array_combine with optional parameters
              *  which may not have been provided
              */
              $emptyValues = array_fill(0, count($parameterNames), null);

              /**
               * += syntax for arrays means: take values from the
               * right-hand side and only add them to the left-hand
               * side if the same key doesn't already exist.
               * 
               * you'll usually want to use array_merge to combine
               * arrays, but this is an interesting use for +=
               * 
               */
              $parameterValues += $emptyValues;
              $this->parameters = array_combine($parameterNames, $parameterValues);
              return true;
        }
        return false;
    }


    private function normalisePath(string $path): string
    {
       $path = trim($path, '/');
       $path = "/{$path}/";

       //remove multiple '/' in a row
       $path = preg_replace('/[\/]{2,}/', '/', $path);
       return $path;
    }

    
    public function dispatch(){ 
      if (is_array($this->handler)) {
        [$class, $method] = $this->handler;

        if (is_string($class)) {
          return (new $class)->{$method}();
        }
        return $class->{$method}();
      }
      return call_user_func($this->handler);
    }
      

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function name(string $name = null): mixed{
     if ($name) {
       $this->name = $name;
       return $this;
      }
     return $this->name;
    }


    
}