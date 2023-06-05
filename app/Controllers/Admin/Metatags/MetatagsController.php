<?php

namespace App\Controllers\Admin\Metatags;

use App\Controllers\Admin\Controller;
use App\Models\Metatags\Metatags;

class MetatagsController extends Controller
{
    /**
     * Método responsável por atualizar as metatags
     */
    public function updateMetatags()
    {
        // Valida formulário
        if (!isset($_POST['description'], $_POST['keywords'])) {
            $this->returnModal('error', 'Erro', 'Certifique-se de preencher todos os campos do formulário.');
        }

        // Array com os dados para update
        $values['description'] = $_POST['description'];
        $values['keywords']    = $_POST['keywords'];

        // Array com as informações atuais do banco
        $old_info = Metatags::getMetatags();

        // Verifica se os dados enviados do form são diferentes dos atuais
        if (!array_diff($old_info, $_POST)) {
            $this->returnModal('info', 'Ok', 'Os dados já estão atualizados.');
        }

        // Atualiza dados
        $update = Metatags::updateMetatags($values);

        // Verifica se houve o update
        if (!$update) {
            $this->returnModal('error', 'Erro', 'Não foi possível atualizar as informações. Tente novamente mais tarde.');
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso', 'Informações atualizadas com sucesso!');
    }
}
