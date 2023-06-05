<?php

namespace App\Controllers\Admin\Error;

use App\Controllers\Admin\Controller;
use App\Models\Error\Error;

class ErrorController extends Controller
{
    /**
     * Método responsável por carregar a tela de erro
     */
    public function index($data)
    {
        // Pega a mensagem do erro
        $errmsg = Error::getErrorMessage($data['errcode']);

        // Carrega a view
        echo $this->view('admin/error/index', [
            'base_url'  => URL_BASE,
            'errcode'   => $data['errcode'],
            'errmsg'    => $errmsg
        ]);
    }
}
