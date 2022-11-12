<?php 

namespace  Framework\View\Engine;

use Framework\View\Engine\Engine;
use Framework\View\Manager;
use Framework\View\View;


class AdvanceEngine  implements Engine
{           
  use HasManager;
  protected $layouts = [];

   public function render(View $view): string 
   {
       $hash = md5($view->path);
       $folder  = __DIR__.'/../../../xxx/framework/View';
       $cached = realpath("{$folder}/{$hash}.php");
       if (!file_exists($hash) || filemtime($view->path) > filemtime($hash))
        {
          $content = $this->conpile(file_get_contents($view->path));
          file_put_contents($cached, $content);  
        }
        extract($this->data);
        
        ob_start();
        include($cached);
        $contents = ob_get_contents();
        ob_end_clean();

        if ($layout = $this->layouts[$cached] ?? null) {
            $contentWithLayout = $view($layout, array_merge(
                $view->data,
                ['content', $content]
            ));
            return $contentWithLayout;
        }

        return $contents;
   }
   

   public function compile(string $template): string
   {   
       //convert the @extends to $this->extends
       $template  = preg_replace_callback("#@extends\(([^)]+)\)#", 
       function($matches){
           return "<?php $this->extends('.$matches[1].'); ?>";
       },  $template);

       //convert @if to $this->if()
       $template = preg_replace_callback("#@extends\(([^)]+)\)#",
       function($matche){
           return "<?php $this->if('. $matches .'): ?>";
       }, $template);

       //convert @ndif to endif
       $template = preg_replace_callback("#@endif#", function($matches){
           return "<?php endif; ?>";
       }, $template);

       //convert {{ }} to print $this->escape()
       $template = preg_replace_callback("#\{\{([^}]+)}\}#", function($matches){
           return "<?php print $this->escape('. $matches[1].'); ?>";
       }, $template);

       //convert {!! !!} to print
       $template = preg_replace_callback('#\{!!([^}]+)!!}\#', function($matches){
           return "<?php print '. $matches[1].';?>";
       }, $template);

       //replace `@***(...)` with `$this->***(...)`
       $template = preg_replace_callback('#@([^(]+)\(([^)]+)\)#',
       function($matches) {
               return '<?php $this->' . $matches[1] . '(' . $matches[2] . '); ?>';
        }, $template);
        

       return $template;
   }

   public function extends(string $template): static 
   {
       $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,1);
       $this->layouts[realpath($backtrace[0]['file'])] = $template;
       return $this;
   }

   public function __call(string $name, $value)
   {
       return $this->manager->useMacro($name, ...$value);
   }



}
