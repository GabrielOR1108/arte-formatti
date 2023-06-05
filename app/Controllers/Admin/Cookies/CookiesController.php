<?php

namespace App\Controllers\Admin\Cookies;

use App\Controllers\Admin\Controller;
use App\Models\Cookies\Cookies;

class CookiesController extends Controller
{
    /**
     * Método responsável por alterar o título e texto do popup de cookies
     */
    public function updateInfo()
    {
        // Valida formulário
        if (!isset($_POST['title'], $_POST['text'])) {
            $this->returnModal('error', 'Erro', 'Certifique-se de preencher todos os campos do formulário.');
        }

        // Array com os dados para update
        $values['title'] = $_POST['title'];
        $values['text']  = $_POST['text'];

        // Array com as informações atuais do banco
        $Cookies = new Cookies();

        // Verifica se os dados enviados do form são diferentes dos atuais
        if (!array_diff($Cookies->toArray(), $_POST)) {
            $this->returnModal('info', 'Ok', 'Os dados já estão atualizados.');
        }

        // Atualiza dados
        $update = $Cookies->updateInfo($values);

        // Verifica se houve o update
        if (!$update) {
            $this->returnModal('error', 'Erro', 'Não foi possível atualizar as informações. Tente novamente mais tarde.');
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso', 'Informações atualizadas com sucesso!');
    }
}
