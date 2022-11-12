<?php 

namespace App\Http\Controllers;

use Framework\Routing\Router;

class RegisterUserController
{
    protected Router $router;

    public function __construct(Router $router){
        $this->router = $router;
    }

    public function handle(){ 
        secure();
        
        $data = validate($_POST, [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:10']
        ]);

        $_SESSION['registered'] = true;

        return redirect($this->router->route('shoow-home-page'));
    }
}

