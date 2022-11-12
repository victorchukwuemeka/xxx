<?php 

namespace  App\Http\Controllers;

use Framework\Routing\Router;


class ShowRegisterUserFormController
{
  protected Router $router;

  public function __construct(Router  $router)
  {
      $this->router = $router;
  }

  public function handle()
  {
    return view('user/register', [
        'router' => $this->router,
    ]);
  }

}
