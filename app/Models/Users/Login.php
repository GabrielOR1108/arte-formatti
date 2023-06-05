<?php

namespace App\Models\Users;

use App\Connection\Connection;

class Login
{
    /**
     * Método responsável por inicar a sessão
     */
    public function init()
    {
        // Verifica o status da sessão
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start(); // Inicia a sessão
        }
    }

    /**
     * Método responsável por logar o usuário
     * @param User $objUser
     */
    public function login($objUser)
    {
        // Inicia a sessão
        self::init();

        // Sessão do usuário
        $_SESSION['user'] = [
            'id'            => $objUser->id,
            'first_name'    => $objUser->first_name,
            'last_name'     => $objUser->first_name,
            'level'         => $objUser->level,
            'favorites'     => $objUser->favorites,
            'email'         => $objUser->email,
            'image'         => $objUser->image,
        ];

        // Instância de conexão com o banco de dados
        $db = new Connection('mw_users');

        // Atualiza o úlitmo login do usuário
        $db->update(['last_login' => date("Y-m-d H:i:s")], "id = {$objUser->id}");
    }

    /**
     * Método responsável por deslogar o usuário
     */
    public static function logout()
    {
        // Verifica se sessão existe
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy(); // Destroi sessão
            header('location: login'); // Redireciona usuário;
            exit;
        }
    }

    /**
     * Metodo responsável por verificar se o usuário está logado
     * @return boolean
     */
    public function isLogged(): bool
    {
        $this->init(); // Inicia a sessao
        return isset($_SESSION['user']['id']); // Verifica se usuário está logado
    }
}
