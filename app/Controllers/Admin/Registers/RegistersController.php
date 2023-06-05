<?php

namespace App\Controllers\Admin\Registers;

use App\Controllers\Admin\Controller;
use App\Models\Gallery\Gallery;
use App\Models\Registers\Register;
use App\Models\Registers\Table;
use App\Models\Users\User;
use PDOException;

class RegistersController extends Controller
{
    /**
     * Método responsável por carregar a view da tabela
     */
    public function table($data)
    {
        $slug = $data['table'];

        $objTable = new Table($slug);

        if ((($objTable->table == MW_USERS_TABLE) || ($objTable->table == MW_RECIPIENTS_TABLE)) ||
            ((!DEV_MODE) && (in_array($objTable->table, DEV_TABLES) || !$this->tableExists($objTable->slug))) ||
            (!in_array($objTable->table, COMMON_USER_TABLES) && !User::isAdmin($_SESSION['user']['id']))
        ) {
            $this->return404();
        }

        // Verifica se a tabela é favorita
        $favorite = (is_array($_SESSION['user']['favorites']) && in_array($objTable->table, $_SESSION['user']['favorites'])) ? 'bi-heart-fill' : 'bi-heart';

        // Carrega a view
        echo $this->view(
            'admin/registers/table',
            [
                'table'         => $objTable->table,
                'slug'          => $objTable->slug,
                'table_title'   => $objTable->title,
                'table_icon'    => $objTable->icon,
                'columns'       => json_encode($objTable->columns),
                'readonly'      => in_array($objTable->table, READONLY_TABLES),
                'export'        => in_array($objTable->table, EXPORT_TABLES),
                'favorite'      => $favorite,
                'router'        => $this->router
            ]
        );
    }

    /**
     * Método responsável por carregar a view de adicionar um novo registro
     */
    public function new($data)
    {
        $slug = $data['table'];
        $objRegister = new Register($slug);

        if (($objRegister->table == MW_USERS_TABLE) || ($objRegister->table == MW_RECIPIENTS_TABLE)) {
            $this->return404();
            exit;
        }

        // Verifica se tabela existe, se não existir carrega a view do erro 404
        $this->tableExists($objRegister->table);

        // Carrega a view
        echo $this->view(
            'admin/registers/new',
            [
                'table'         => $objRegister->table,
                'slug'          => $objRegister->slug,
                'table_title'   => $objRegister->title,
                'table_icon'    => $objRegister->icon,
                'inputs'        => $objRegister->getInputs(create: true),
                'router'        => $this->router
            ]
        );
    }

    /**
     * Método responsável por carregar a view de adicionar um novo registro
     */
    public function edit($data)
    {
        $slug = $data['table'];
        $objRegister = new Register($slug);

        if (($objRegister->table == MW_USERS_TABLE) || ($objRegister->table == MW_RECIPIENTS_TABLE)) {
            $this->return404();
        }

        // Chave identificadora do registro
        $id = $data['id'];

        // Verifica se tabela existe, se não existir carrega a view do erro 404
        $this->tableExists($objRegister->table);

        $values = $objRegister->getRegisterById($id);
        if (!$values) {
            $this->return404();
        }

        // Carrega a view
        echo $this->view(
            'admin/registers/edit',
            [
                'id'            => $id,
                'table'         => $objRegister->table,
                'slug'          => $objRegister->slug,
                'table_title'   => $objRegister->title,
                'table_icon'    => $objRegister->icon,
                'inputs'        => $objRegister->getInputs(create: true),
                'values'        => $values,
                'router'        => $this->router
            ]
        );
    }
    /**
     * Método responsável por carregar a view de visualizar um registro
     */
    public function see($data)
    {
        $slug = $data['table'];
        $objRegister = new Register($slug);

        if (($objRegister->table == MW_USERS_TABLE) || ($objRegister->table == MW_RECIPIENTS_TABLE)) {
            $this->return404();
            exit;
        }

        // Chave identificadora do registro
        $id = $data['id'];

        // Verifica se tabela existe, se não existir carrega a view do erro 404
        $this->tableExists($objRegister->table);

        // Carrega a view
        echo $this->view(
            'admin/registers/see',
            [
                'id'            => $id,
                'table'         => $objRegister->table,
                'slug'          => $objRegister->slug,
                'table_title'   => $objRegister->title,
                'table_icon'    => $objRegister->icon,
                'inputs'        => $objRegister->getInputs(),
                'values'        => $objRegister->getRegisterById($id, is_view: true),
                'router'        => $this->router
            ]
        );
    }

    /**
     * Método responsável por obter os registros da tabela
     */
    public function getRegisters()
    {
        // Se não houver requisição GET retorna popup
        $slug = $_GET['table'];

        if (!$_GET) {
            $this->returnModal('error', 'Erro', 'Houve um erro ao carregar os registros. tente novamente mais tarde.');
        }

        if (!isset($_GET['table'])) {
            $this->returnModal('error', 'Erro', 'Tabela não encontrada. tente novamente mais tarde.');
        }

        $Table = new Table($slug);

        // Retorna os registros em formato JSON
        header('Content-Type: application/json');
        echo json_encode($Table->getRegisters($_GET));
        exit;
    }

    /**
     * Método responsável por alterar o valor de um campo booleano da tabela
     */
    public function activate($data)
    {
        // Obtém as informações enviadas pelo methodo PATCH via ajax
        $_PATCH = $data;

        // Valida se todos os dados necessários foram enviados
        if (!isset($_PATCH['table'], $_PATCH['id'], $_PATCH['field'], $_PATCH['value'])) {
            $this->returnModal('error', 'Erro', 'Houve um erro ao tentar atualizar o registro. Tente novamente mais tarde.');
        }

        // Update
        $update = Table::activateBooleanField($_PATCH['table'], $_PATCH['id'], $_PATCH['field'], $_PATCH['value']);

        // Verifica se houve erro no update
        if (!$update) {
            $this->returnModal('error', 'Erro', 'Houve um erro ao tentar atualizar o registro. Tente novamente mais tarde.');
        }

        $this->returnModal('success', 'Sucesso', 'Registro atualizado com sucesso!');
    }

    /**
     * Método responsável por inserir dados na tabela
     */
    public function insert($data)
    {
        // Verifica se houve o $_POST
        if (!$_POST) {
            $this->returnModal('error', 'Erro', 'Erro inesperado ao tentar adicionar o registro. Tente novamente mais tarde.');
        }

        // Array com $_POST
        $post = ($_POST) ? $_POST : [];

        // Array com $_POST
        $files = ($_FILES) ? $_FILES : [];

        $objRegister = new Register($data['table']);
        $insert = $objRegister->insert($post, $files);

        // Verifica se houve uma exception
        if ($insert instanceof PDOException) {
            $this->returnModal('error', 'Erro', $insert->getMessage());
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso!', 'Novo registro inserido com sucesso!', "mw-admin/registers/{$data['table']}");
    }

    /**
     * Método responsável por atualizar um registro
     */
    public function update($data)
    {
        // Verifica se houve o $_POST
        if (!$_POST) {
            $this->returnModal('error', 'Erro', 'Erro inesperado ao tentar adicionar o registro. Tente novamente mais tarde.');
        }

        // Array com $_POST
        $post = ($_POST) ? $_POST : [];

        // Array com $_POST
        $files = ($_FILES) ? $_FILES : [];

        // Chama o método de atualizar registro
        $objRegister = new Register($data['table'], $data['id']);
        $update = $objRegister->update($post, $files);

        // Verifica se houve uma exception
        if ($update instanceof PDOException) {
            $this->returnModal('error', 'Erro', $update->getMessage());
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso!', 'Registro atualizado com sucesso!', "mw-admin/registers/{$objRegister->slug}");
    }

    public function updateGalleryImageName($data)
    {
        $table     = $data['table'];
        $slug      = str_replace('_', '-', $table);
        $id        = $data['id'];
        $field     = $data['field'];
        $img_index = $data['img_index'];
        $img_name  = $data['img_name'];

        $objTable = new Table($slug);

        $Register = $objTable->getRegisterById($id);

        $objGallery = new Gallery($Register[$field], $table, $field, $id);

        $objGallery->gallery[$img_index]['name'] = $img_name;

        if (!$objGallery->updateGalleryImageName([$field => $objGallery->getGalleryAsJSON()])) {
            $this->returnModal('error', 'Erro!', 'Não foi possível atualizar o nome da imagem, tente novamente mais tarde.');
        }

        $this->returnModal('success', 'Sucesso!', 'Nome atualizado com sucesso!');
    }

    public function deleteGalleryImage($data)
    {
        $table     = $data['table'];
        $slug = str_replace('_', '-', $table);
        $id        = $data['id'];
        $field     = $data['field'];
        $img_index = $data['img_index'];

        $objTable = new Table($slug);

        $Register = $objTable->getRegisterById($id);
        $gallery = new Gallery($Register[$field], $table, $field, $id);

        if (!$gallery->deleteGalleryImage($table, $img_index)) {
            $this->returnModal('error', 'Erro!', 'Não foi possível excluir imagem, tente novamente mais tarde.');
        }

        if (!$gallery->updateGalleryImageName([$field => $gallery->getGalleryAsJSON()])) {
            $this->returnModal('error', 'Erro!', 'Não foi possível atualizar o nome da imagem, tente novamente mais tarde.');
        }

        $this->returnModal('success', 'Sucesso!', 'Imagem excluída com sucesso!');
    }

    /**
     * Método responsável por excluir dados da tabela
     */
    public function delete($data)
    {
        // Valida dados
        if (empty($data) || !isset($data['table'], $data['data'])) {
            $this->returnModal('error', 'Erro', 'Houve um erro ao tentar deletar o registro. Tente novamente mais tarde.');
        }

        $objTable = new Table(str_replace('_', '-', $data['table']));
        $delete = $objTable->deleteRegisters($data['data']);

        // Se houver excpetion retornar a mensagem de erro
        if ($delete instanceof PDOException) {
            $this->returnModal('error', 'Erro', $delete->getMessage());
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso', 'Registros excluídos com sucesso!');
    }

    /**
     * Método responsável por adicionar uma tabela aos favoritos do usuário
     * 
     */
    public function favorite($data)
    {
        // Obtém as informações enviadas pelo metodo PATCH via ajax
        $_PATCH = $data;

        // Tabela a ser adicionada
        $table = $_PATCH['table'];

        // Verifica se tabela existe, caso não exista, informa o usuário
        if (!$this->tableExists($table)) {
            $this->returnModal('error', 'Erro', 'Tabela não encontrada.');
        }

        // Instância de User
        $objUser = User::getUserById($_SESSION['user']['id']);

        // Realiza o update da tabela
        if (!$objUser->updateFavorites($table)) {
            $this->returnModal('error', 'Erro', 'Tente novamente mais tarde');
        }

        $this->returnModal('success', 'Sucesso!', 'Tabela adicionada aos favoritos com sucesso!');
    }
}
