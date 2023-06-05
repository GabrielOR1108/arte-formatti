<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
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

        // Função para limpar telefone 
        $template->registerFunction('clean_telephone', function ($string) {
            return preg_replace('/[@\.\;\(\)\-\" "]+/', '', $string);
        });

        // Adiciona dados ao header
        $template->addData([
            'url_base' => URL_BASE . '/',
        ], 'site/partials/head');

        return $template->render($view, $data);
    }

    /**
     * Método responsável por retornar uma página 404 padrão
     */
    public function return404()
    {
        $this->router->redirect('site.error.index', ['errcode' => 404]);
        exit;
    }
}
