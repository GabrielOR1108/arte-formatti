<?php

namespace App\Controllers\Admin\Recipients;

use App\Controllers\Admin\Controller;
use App\Models\Recipients\Recipients;
use PDOException;

class RecipientsController extends Controller
{

    /**
     * Método responsável por obter os registros da tabela
     */
    public function getRecipients()
    {
        // Se não houver requisição GET retorna popup
        if (!$_GET) {
            $this->returnModal('error', 'Erro', 'Houve um erro ao carregar os registros. tente novamente mais tarde.');
        }

        // Retorna os registros em formato JSON
        $Recipients = new Recipients();
        header('Content-Type: application/json');
        echo json_encode($Recipients->getRecipients($_GET));
        exit;
    }

    /**
     * Método responsável por obter os dados de um destinatário pelo id
     */
    public function getRecipient()
    {
        // Se não houver requisição GET retorna popup
        if (!$_GET || !isset($_GET['id'])) {
            $this->returnModal('error', 'Erro', 'Houve um erro ao carregar o destinatário. tente novamente mais tarde.');
        }

        // Instância de Recipients
        $objRecipients = Recipients::getRecipientById($_GET['id']);

        // Array com a data necessária para a edição
        $recipientData = [
            'id'    => $objRecipients->id,
            'name'  => $objRecipients->name,
            'email' => $objRecipients->email,
        ];

        // Retorna a data em formato JSON
        header('Content-Type: application/json');
        echo json_encode($recipientData);
        exit;
    }

    public function addRecipient()
    {
        // Se não houver requisição POST retorna popup
        if (!$_POST) {
            $this->returnModal('error', 'Erro', 'Não foi possível adicionar o destinatário. tente novamente mais tarde.');
        }

        $values = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
        ];

        // Chama o método de inserir destinatário
        $insert = Recipients::insert($values);

        // Verifica se houve uma exception
        if ($insert instanceof PDOException) {
            $this->returnModal('error', 'Erro', $insert->getMessage());
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso!', 'Destinatário inserido com sucesso!');
    }

    /**
     * Método responsável por editar um destinatário
     * 
     */
    public function editRecipient($data)
    {
        // Obtém o id do usuário
        $id = $data['id'];

        // Array para valores name e email
        $values = [
            'name'   => $data['name'],
            'email'  => $data['email'],
        ];

        // Atualiza registro
        $update = Recipients::update($id, $values);

        // Verifica se houve uma exception
        if ($update instanceof PDOException) {
            $this->returnModal('error', 'Erro', $update->getMessage());
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso!', 'Destinatário atualizado com sucesso!');
    }
}
