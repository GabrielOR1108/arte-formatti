<?php

namespace App\Controllers\Admin\Users;

use App\Controllers\Admin\Controller;
use App\Models\Users\User;
use PDOException;

class UserController extends Controller
{
    /**
     * Método responsável por carregar a view dos usuários
     */
    public function users(): void
    {
        // Instância de User
        $objUser = new User();

        // Carrega a view
        echo $this->view('admin/user/users', [
            'table'     => MW_USERS_TABLE,
            'columns'   => $objUser->getData(),
            'router'    => $this->router
        ]);
    }

    /**
     * Método responsável por carregar a view da cadastrar um novo usuário
     */
    public function new(): void
    {
        echo $this->view('admin/user/new', [
            'user_levels' => User::getUserLevels(),
            'img_width'   => IMAGE_FIELDS['mw_users']['image']['width'],
            'img_height'  => IMAGE_FIELDS['mw_users']['image']['height'],
            'router'      => $this->router
        ]);
    }

    /**
     * Método responsável por carregar a view do perfil do usuário logado
     */
    public function profile(): void
    {
        $objUser = User::getUserById($_SESSION['user']['id']);

        echo $this->view('admin/user/profile', [
            'user_id'       => $objUser->id,
            'first_name'    => $objUser->first_name,
            'last_name'     => $objUser->last_name,
            'email'         => $objUser->email,
            'image'         => $objUser->image,
            'user_avatar'   => $_SESSION['user']['image'],
            'router'        => $this->router
        ]);
    }

    /**
     * Método responsável por atualizar as informações do perfil do usuário [nome, sobrenome e email]
     */
    public function updateProfile(): void
    {
        // Valida formulário
        if (!isset($_POST['first_name'], $_POST['last_name'], $_POST['email'])) {
            $this->returnModal('error', 'Erro', 'Erro inesperado ao atualizar os dados. Tente novamente mais tarde.');
        }

        // Verifica se usuário existe
        $objUser = User::getUserById($_SESSION['user']['id']);

        // Valida instancia de usuario
        if (!$objUser instanceof User) {
            $this->returnModal('error', 'Erro', 'Usuário não encontrado. Tente novamente mais tarde.');
        }

        // Verifica se os dados enviados no formulário são os mesmos que estão cadastrados
        if ($objUser->first_name == $_POST['first_name'] && $objUser->last_name == $_POST['last_name'] && $objUser->email == $_POST['email']) {
            $this->returnModal('info', 'Ok', 'Os dados já estão atualizados.');
        }

        // Verifica se existe usuário com o email informado
        $emailUser = User::getUserByEmail($_POST['email']);
        if ($emailUser instanceof User && $emailUser->id != $objUser->id) {
            $this->returnModal('warning', 'Email em uso', 'O email informado já está sendo utilizado por outro usuário.');
        }

        // Requisita update profile
        $update = $objUser->updateUser([
            'first_name'    => $_POST['first_name'],
            'last_name'    => $_POST['last_name'],
            'email'    => $_POST['email']
        ]);

        // Verifica se houve o update
        if (!$update) {
            $this->returnModal('error', 'Erro', 'Não foi possível atualizar os dados. Tente novamente mais tarde.');
        }


        $_SESSION['user']['first_name'] = $_POST['first_name'];
        $_SESSION['user']['last_name']  = $_POST['last_name'];
        $_SESSION['user']['email']      = $_POST['email'];


        // Retorna sucesso
        $this->returnModal('success', 'Sucesso', 'Dados atualizados com sucesso!');
    }

    /**
     * Método responsável por atualizar a senha do usuário
     */
    public function updatePassword(): void
    {
        // Valida formulário
        if (!isset($_POST['old_password'], $_POST['new_password'], $_POST['repeat_new_password'])) {
            $this->returnModal('error', 'Erro', 'Erro inesperado ao atualizar a senha. Tente novamente mais tarde.');
        }

        // Verifica se usuário existe
        $objUser = User::getUserById($_SESSION['user']['id']);

        // Valida instancia de usuario
        if (!$objUser instanceof User) {
            $this->returnModal('error', 'Erro', 'Usuário não encontrado. Tente novamente mais tarde.');
        }

        // Valida se senha informada é realmente a senha da conta
        if (!password_verify($_POST['old_password'], $objUser->password)) {
            $this->returnModal('error', 'Senha incorreta', 'Senha antiga está incorreta.');
        }

        // Valida se as senhas informadas são iguais
        if ($_POST['new_password'] != $_POST['repeat_new_password']) {
            $this->returnModal('error', 'Senhas divergentes', 'As senhas informadas não conferem.');
        }

        // Requisita update
        $update = $objUser->updateUser([
            'password' => password_hash($_POST['new_password'], PASSWORD_DEFAULT)
        ]);

        // Verifica se houve o update
        if (!$update) {
            $this->returnModal('error', 'Erro', 'Não foi possível atualizar a senha. Tente novamente mais tarde.');
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso', 'Senha atualizada com sucesso!');
    }

    /**
     * Método responsável por criar novo usuário
     */
    public function createUser(): void
    {
        // Verifica se houve o $_POST
        if (!$_POST) {
            $this->returnModal('error', 'Erro', 'Erro inesperado ao tentar cadastrar usuário. Tente novamente mais tarde.');
        }

        // Array com $_POST
        $post = ($_POST) ? $_POST : [];

        // Array com $_POST
        $files = ($_FILES) ? $_FILES : [];

        // Chama o método de inserir registro
        $insert = User::createUser($post, $files);

        // Verifica se houve uma exception
        if ($insert instanceof PDOException) {
            $this->returnModal('error', 'Erro', $insert->getMessage());
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso!', 'Usuário cadastrado com sucesso!', "mw-admin/user/users");
    }

    /**
     * Método responsável por excluir o usuário
     */
    public function deleteUser(): void
    {
        // Valida formulário
        if (!isset($_POST['password'])) {
            $this->returnModal('error', 'Erro', 'Por favor informe a senha.');
        }

        // Verifica se usuário existe
        $objUser = User::getUserById($_SESSION['user']['id']);

        // Valida instancia de usuario
        if (!$objUser instanceof User) {
            $this->returnModal('error', 'Erro', 'Usuário não encontrado. Tente novamente mais tarde.');
        }

        // Verifica se a senha informada confere com a senha do usuário
        if (!password_verify($_POST['password'], $objUser->password)) {
            $this->returnModal('error', 'Senha incorreta', 'A senha informada está incorreta.');
        }

        // Verifica se houve a exclusão
        if (!$objUser->deleteUser()) {
            $this->returnModal('error', 'Erro', 'Não foi possível excluir o usuário. Tente novamente mais tarde.');
        }

        // Retorna sucesso e destroi sessão
        session_destroy();
        $this->returnModal('success', 'Sucesso', 'Usuário excluído com sucesso!');
    }

    /**
     * Método responsável por atualizar a imagem de perfil do usuário
     */
    public function updateAvatar()
    {
        // Valida arquivos
        if (!$_FILES || !isset($_FILES['image'])) {
            $this->returnModal('error', 'Erro', 'Erro ao tentar fazer o upload da imagem. Tente novamente mais tarde.');
        }

        // Obtém o arquivo
        $image = $_FILES['image'];

        // Instância de usuário
        $objUser = User::getUserById($_SESSION['user']['id']);

        // Obtém o novo nome do arquivo
        $new_filename = $objUser->updateAvatar($image);

        // Verifica se o retorno foi boolean
        if (is_bool($new_filename)) {
            $this->returnModal('error', 'Erro', 'Erro ao tentar fazer o upload da imagem. Tente novamente mais tarde.');
        }

        // Atribui o novo nome da imagem na variavel $_SESSION
        $_SESSION['user']['image'] = $new_filename;

        // Retorna sucesso
        echo true;
        exit;
    }

    /**
     * Método responsável por atualizar o nível do usuário
     */
    public function updateLevel()
    {
        // Valida POST
        if (!$_POST) {
            $this->returnModal('error', 'Erro', 'Erro ao tentar atualizar o nível do usuário. Tente novamente mais tarde.');
        }

        // Instância de usuário
        $objUser = User::getUserById($_POST['id']);

        // Verifica se houve atualização
        if (!$objUser->updateLevel($_POST['level'])) {
            $this->returnModal('error', 'Erro', 'Não foi possível atualizar o nível do usuário. Tente novamente mais tarde.');
        }

        // Retorna sucesso
        $this->returnModal('success', 'Sucesso', 'Nível de usuário atualizado com sucesso!');
    }

    /**
     * Método responsável por obter os registros da tabela
     */
    public function getUsers()
    {
        // Se não houver requisição GET retorna popup
        if (!$_GET) {
            $this->returnModal('error', 'Erro', 'Houve um erro ao carregar os registros. tente novamente mais tarde.');
        }

        // Retorna os registros em formato JSON
        header('Content-Type: application/json');
        echo json_encode(User::getUsers($_GET));
        exit;
    }
}
