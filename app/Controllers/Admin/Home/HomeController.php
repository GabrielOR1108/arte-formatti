<?php

namespace App\Controllers\Admin\Home;

use App\Controllers\Admin\Controller;
use App\Models\Model;
use App\Models\Users\User;

class HomeController extends Controller
{
    /**
     * Método responsável por a tela inicial da home
     */
    public function index()
    {
        echo $this->view('admin/home/home', [
            'first_name'    => $_SESSION['user']['first_name'],
            'group_menus'   => Model::getSystemMenu(User::isAdmin($_SESSION['user']['level'])),
            'favorites'     => Model::getFavoritesMenu($_SESSION['user']['favorites']),
            'router'        => $this->router
        ]);
    }

    /**
     * Método responsável por redirecionar o usuário para home
     * quando tentar acessar a página de login estando logado
     */
    public function redirectHome()
    {
        header('location: home');
        exit;
    }
}
