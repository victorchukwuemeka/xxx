<?php 

namespace Framework\View\Engine;

use Framework\View\Engine\Engine;
use Framework\View\Manager;
use Framework\View\View;
use function view;

class PhpEngine implements Engine
{
    use HasManager;
      
    protected array $layouts = [];

    public function render(View $view): string
    {
        extract($this->data);
        ob_start();
        include($view->path);

        $contents = ob_get_contents();
        ob_end_clean();

        if($layout = $this->layouts[$view->path] ?? null ) 
        {
          
          $layoutWithContents = view($layout, array_merge(
            $view->data,
            ['contents' => $contents]
          ));
          return $layoutWithContents;
        }
        return $contents;
    }
    
   
    public function __call(string $name, $values)
    {
       return $this->manager->useMacro($name, ...$values);
    }

    public function extends(string $template): static 
    {
      
      $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,1);
      $this->layouts[realpath($backtrace[0]['file'])] = $template;
      return $this;
    }
    
    public function includes(string $template, $data = []): void 
    {
      print  view($template, $data);
    }


    
}