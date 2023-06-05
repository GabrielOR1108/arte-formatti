<?php

namespace App\Controllers\Admin\Users;

use App\Controllers\Admin\Controller;
use App\Models\Users\User;
use App\Models\Users\Login;
use App\Models\Email\Email;
use App\Models\Users\Recover;

class LoginController extends Controller
{
    /**
     * Método responsável por renderizar a tela de login
     */
    public function index()
    {
        echo $this->view('admin/login/index', [
            'base_href'   => URL_BASE . '/mw-admin',
            'title'       => 'Login',
            'description' => 'Informe seus dados de login para acessar o sistema.',
            'router'      => $this->router
        ]);
    }

    /**
     * Método responsável por renderizar a tela de alterar a senha
     */
    public function changePassView($data)
    {
        echo $this->view('admin/login/change-pass', [
            'base_href' => URL_BASE . '/',
            'hash'      => $data['hash'],
            'router'    => $this->router

        ]);
    }

    /**
     * Método responsável por realizar o login
     */
    public function login(): void
    {
        // Valida email e password
        if (!isset($_POST['email'], $_POST['password'])) {
            echo $this->returnModal('error', 'Acesso negado', 'Por favor, informe seus dados de acesso.');
            exit;
        }

        // Instancia obj User
        $objUser = User::getUserByEmail($_POST['email']);

        if (!$objUser instanceof User) {
            echo $this->returnModal('error', 'Acesso negado', 'Por favor, informe seus dados de acesso.');
            exit;
        }

        // Verifica se usuário é ativo
        if (!$objUser->active) {
            echo $this->returnModal('error', 'Acesso negado', 'Por favor, informe seus dados de acesso.');
            exit;
        }

        // Valida instância e senha
        if (!password_verify($_POST['password'], $objUser->password)) {
            echo $this->returnModal('error', 'Acesso negado', 'Email ou Senha incorretos.');
            exit;
        }

        // Loga e redireciona o usuário
        (new Login)->login($objUser);
        echo $this->returnModal('success', 'Sucesso!', 'Logado com sucesso! Você será redirecionado em instantes', 'mw-admin/home');
    }

    /**
     * Método responsável por deslogar o usuário do sistema
     */
    public function logout(): void
    {
        Login::logout();
    }

    /**
     * Método responsável por enviar o email de redefinição de senha
     */
    public function forgotPass()
    {
        // Valida formulário
        if (!isset($_POST['email'])) {
            echo $this->returnModal('error', 'Informe o email', 'Por favor, informe o email.');
            exit;
        }

        // Valida email
        $email = Email::validateEmail($_POST['email']);
        if ($email == null) {
            echo $this->returnModal('error', 'Erro', 'Email inválido. Por favor, verifique se o digitou corretamente.');
            exit;
        }

        // Valida se usuário existe
        $objUser = User::getUserByEmail($email);
        if (!$objUser instanceof User) {
            echo $this->returnModal('warning', 'Usuário não encontrado', 'O email informado não pertence a nenhum usuário.');
            exit;
        }

        // Gera a hash e insere no banco
        $hash = Recover::generateHash($email);

        // Valida se a hash foi retornada
        if (!is_string($hash)) {
            echo $this->returnModal('error', 'Erro', 'Ocorreu um erro inesperado. Tente novamente mais tarde.');
            exit;
        }

        $body = 'Acesse o link para recuperar a senha: <a href="' . URL_BASE . '/mw-admin/change-pass/' . $hash . '">Recuperar Senha</a>';

        if (!Email::sendEmail('Recuperação de senha', $body, [$email])) {
            echo $this->returnModal('error', 'Erro', 'Houve um erro ao tentar enviar o email de recuperação. Tente novamente mais tarde.');
            exit;
        }

        echo $this->returnModal('success', 'Sucesso', 'Email enviado com sucesso! Verifique sua caixa de entrada e não se esqueça de conferir a caixa de spam.');
        exit;
    }

    /**
     * Método responsável por realizar a troca da senha
     */
    public function changePass(): void
    {
        // Valida hash
        if (!isset($_POST['hash'])) {
            echo $this->returnModal('error', 'Erro', 'Erro inesperado, tente novamente mais tarde.');
            exit;
        }

        // Valida formulário
        if (!isset($_POST['password'], $_POST['password-repeat'])) {
            echo $this->returnModal('error', 'Erro', 'Preencha os dados corretamente.');
            exit;
        }

        // Confere se as senhas conferem
        if ($_POST['password'] != $_POST['password-repeat']) {
            echo $this->returnModal('error', 'Erro', 'As senhas informadas não conferem.');
            exit;
        }

        // Obtém uma instância de Recover ou null
        $objRecover = Recover::getRecoverByHash($_POST['hash']);

        // Valida se o objeto é uma instância de Recover
        if (!$objRecover instanceof Recover) {
            echo $this->returnModal('error', 'Erro', 'Pedido de recuperação de senha não encontrado. Ele pode já ter sido usado ou ter expirado.');
            exit;
        }

        // Busca o usuário pelo email encontrado
        $objUser = User::getUserByEmail($objRecover->email);

        // Altera a senha do banco no objUser para a senha informada no formulário
        $objUser->password = $_POST['password'];

        // Valida usuário
        if (!$objUser instanceof User) {
            echo $this->returnModal('error', 'Erro', 'O email que requisitou a recuperação de senha não existe.');
            exit;
        }

        if (!$objUser->changePass($_POST['hash'])) {
            echo $this->returnModal('error', 'Erro', 'Não foi possível alterar a senha. Tente novamente mais tarde');
            exit;
        }

        echo $this->returnModal('success', 'Sucesso!', 'Senha alterada com sucesso! Você será redirecionado para a tela de login em instantes.', 'mw-admin/login');
    }
}
