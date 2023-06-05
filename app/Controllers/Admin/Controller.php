<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Model;
use App\Models\Users\Login;
use App\Models\Users\User;
use League\Plates\Engine;

class Controller extends BaseController
{
    /**
     * Método responsável por renderizar a view 
     * @param string $view
     * @param array $data
     * @return string
     */
    public function view(string $view, array $data = []): string
    {
        // Caminho para a pasta onde estão as views
        $viewsPath = dirname(__FILE__, 4) . '/views';
        $template = new Engine($viewsPath);

        // Adiciona dados ao header
        $template->addData([
            'url_base' => URL_BASE . '/mw-admin',
        ], 'admin/partials/header');

        // Verifica se o usuário está logado para adicionar a data aos partials que dependem do login
        if ((new Login)->isLogged()) {

            // Adiciona dados à navbar
            $template->addData([
                'first_name'    => $_SESSION['user']['first_name'],
                'user_avatar'   => $_SESSION['user']['image'],
                'router'        => $this->router
            ], 'admin/partials/navbar');

            // Adiciona dados à sidebar
            $template->addData([
                'first_name'    => $_SESSION['user']['first_name'],
                'user_avatar'   => $_SESSION['user']['image'],
                'user_level'    => User::getLevelName($_SESSION['user']['level']),
                'admin'         => User::isAdmin($_SESSION['user']['level']),
                'group_menus'   => Model::getSystemMenu(User::isAdmin($_SESSION['user']['level'])),
                'favorites'     => Model::getFavoritesMenu($_SESSION['user']['favorites']),
                'dev_mode'      => DEV_MODE,
                'router'        => $this->router
            ], 'admin/partials/sidebar');
        }

        return $template->render($view, $data);
    }

    /**
     * Método responsável por retornar uma página 404 padrão
     */
    public function return404()
    {
        $this->router->redirect('admin.error.index', ['errcode' => 404]);
        exit;
    }

    /**
     * Método responsável por verificar se a tabela existe
     * @param string $table
     * @return bool
     */
    public function tableExists(string $table): bool
    {
        return Model::tableExists($table);
    }
}
