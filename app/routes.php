<?php


use App\Htpp\Controllers\Products;
use App\Http\Controllers\Products\ListProductsController;
use App\Http\Controllers\ShowHomePageController;
use App\Http\Controllers\Services\ShowServiceController;
use App\Http\Controllers\ShowRegisterUserFormController;
//use App\Http\Controllers\RegisterUserController;
//use App\Http\Controllers\Users\ShowRegisterUserFormController;
//use App\Http\Controllers\Users\RegisterUserController;



use Framework\Routing\Router;



return function(Router $router){

    //$router->add('GET', '/', fn()=>'victor');
    
    $router->add('GET', '/', [ShowHomePageController::class, 'handle'], )
         ->name('show-home-page');
    //$router->add('GET', '/', fn()=>'good',);
    
   // $ll = include_once __DIR__ .'/../framework/helpers.php';
    $router->add('GET', '/home', 
           // fn()=>"victor",
            fn()=>view('home',['number'=> 32]),
           //fn()=>var_dump($oo),
    );

    $router->add('GET', '/old-page', fn()=>$router->redirect('/old-page'),);

    $router->add('GET', '/has-server-error', fn()=> throw new exception(),);
    //$router->add('GET','/numb', fn()=>'good');

    $router->add('GET', '/has-validator-error', fn()=> $router->dispatchNotAllowed(),);

    $router->errorHandler(404, fn()=>"whoops!");

    $router->add(
        'GET', 'products/view/{product}',
        function () use ($router){
            $parameters = $router->current()->parameters();

            return  view('products/view', [
                'product' => $parameters['product'],
                'scary' => '<script>alert("boo!")</script>',
            ]);
            return "product is {$parameters['product']}";
        },
    );

    $router->add(
        'GET', '/services/view/{service?}',
        function () use ($router) {
            $parameters = $router->current()->parameters();
            if (empty($parameters['service'])) {
                return "all the services";
            }
            return "service is {$parameters['service']}";
        },
    );

   $router->add(
        'GET', '/products/{page?}',
        [new ListProductsController($router), 'handle'],
   )->name('product-list');

   /*$router->add(
       'GET', '/register',
       [new ShowRegisterUserFormController($router), 'handle'],
   )->name('show-register-user'); */ 
   
  /* $router->add( 'POST', '/register',
       [new RegisterUserController($router), 'handle'],
   )->name('register-user');
   */


};
