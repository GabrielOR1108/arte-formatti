<?php

namespace App\Controllers\Site\Error;

use App\Controllers\Site\Controller;
use App\Models\Error\Error;

class ErrorController extends Controller
{
    /**
     * Método responsável por carregar a tela de erro
     */
    public function home($data)
    {
        // Pega a mensagem do erro
        $errmsg = Error::getErrorMessage($data['errcode']);

        // Carrega a view
        echo $this->view('site/error/index', [
            'errcode'   => $data['errcode'],
            'errmsg'    => $errmsg
        ]);
    }
}
