<?php

namespace App\Controllers\Admin\Email;

use App\Controllers\Admin\Controller;
use App\Models\Email\Email;
use App\Models\Registers\Register;
use Exception;

class EmailController extends Controller
{
    /**
     * Método responsável por atualizar o cabeçalho e rodapé do email
     */
    public function updateEmailLayout()
    {
        if (!$_FILES) {
            $this->returnModal('error', 'Erro', 'Erro ao tentar fazer o upload dos arquivos. Tente novamente mais tarde.');
        }

        // Instância de Email
        $objEmail = new Email();

        $Register = new Register(str_replace('_', '-', $objEmail->table), $objEmail->id);

        try {
            $update = $Register->update([], $_FILES);
        } catch (Exception $e) {
            $this->returnModal('error', 'Erro', $update->getMessage());
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso!', 'Layout atualizado com sucesso!');
    }
}
