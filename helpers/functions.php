<?php 


use Framework\View\View;
use Framework\View\Manager;
use Framework\Validation\Rule\Rule;


use Framework\View\Engine\BasicEngine;
use Framework\View\Engine\AdvanceEngine;
use Framework\View\Engine\PhpEngine;
//new BasicEngine();


if (!function_exists('view')) {
    function view(string $template, array $data=[]) //View\
    {
        static $manager;

        if (!$manager) {
            $manager = new Manager();

            //add the path of the views folder
            //so the manager will know where to find the view
            $manager-> addPath(__DIR__.'/../resources/views');

            //adding of new engine classes with their 
            //extentions to be able to pick the 
            //specific template 
            $manager->addEngine('basic.php', new BasicEngine());
            $manager->addEngine('advanced.php', new AdvanceEngine());
            $manager->addEngine('php', new PhpEngine());

            //let's add macos here for now
            $manager->addMacro('escape', fn($value)=> htmlspecialchars($value));
            $manager->addMacro('includes', fn($params)=> print view($params));
            
        }
       return $manager->resolve($template, $data);
    }

    function redirect(string $url){
        header("Location: {$url}");
        exist;
    }
    
}

//if (!function_exists('redirect')) {
  //  header("Location: {$url}");
   // exist;
//}

if (!function_exists('validate')) {
    function validate(array $data, array $rules){
        static $manager;

        if(!$manager){
            $manager = new Validation\Manager();
          
            //lets add the rule that came with the validation
            $manager->addRule('required', new Validation\Rule\RequiredRule());
            $manager->addRule('email', new Validation\Rule\EmailRule());
            $manager->addRule('min', new Validation\Rule\MinRule());
        }

        return $manager->validate($data, $rules);
    }
}


if (!function_exists('crsf')) {
    function crsf(){
        $_SESSION['token'] = bin2hex(random_bytes(32));
        return $_SESSION['token'];
    }  
}

if (!function_exists('secure')) {
    function secure(){
        if (!isset($_POST['csrf']) || !isset($_SESSION['token']) ||
        !hash_equals($_SESSION['token'], $_POST['csrf'])){
            throw new Exception("CSRF token mismatch");
            
        }
    }
}


if (!function_exists('victor')) {
    function victor(){
        return $author ;
    }
}    