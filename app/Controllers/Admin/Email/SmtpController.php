<?php

namespace App\Controllers\Admin\Email;

use App\Controllers\Admin\Controller;
use App\Models\Email\Smtp;

class SmtpController extends Controller
{
    /**
     * Método responsável por atualizar a configuração SMTP
     */
    public function updateSmtpConfig()
    {
        // Valida se todos os campos do formulário estão preenchidos
        if (!isset($_POST['host'], $_POST['user'], $_POST['pass'], $_POST['name'], $_POST['auth'], $_POST['port'])) {
            $this->returnModal('error', 'Erro', 'Certifique-se de preencher todos os campos do formulário.');
        }

        // Array com os dados para update
        $values['host'] = $_POST['host'];
        $values['user'] = $_POST['user'];
        $values['pass'] = $_POST['pass'];
        $values['name'] = $_POST['name'];
        $values['auth'] = $_POST['auth'];
        $values['port'] = $_POST['port'];

        // Array com as configurações atuais do banco
        $old_config = Smtp::getSmtpConfig();

        // Verifica se os dados enviados do form são diferentes dos atuais
        if (!array_diff($old_config, $_POST)) {
            $this->returnModal('info', 'Ok', 'Os dados já estão atualizados.');
        }

        // Atualiza dados
        $update = Smtp::updateSmtpConfig($values);

        // Verifica se houve o update
        if (!$update) {
            $this->returnModal('error', 'Erro', 'Não foi possível atualizar as configurações SMTP. Tente novamente mais tarde.');
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso', 'Configurações SMTP atualizadas com sucesso!');
    }
}
