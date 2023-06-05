<?php

namespace App\Middlewares\Admin;

use App\Models\Users\Login;
use CoffeeCode\Router\Router;

class LoginMiddleware
{
    public function handle(Router $router): bool
    {
        if ((new Login)->isLogged()) {
            $router->redirect('admin.home.home');
        }
        return true;
    }
}
