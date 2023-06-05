<?php

namespace App\Models\Email;

use App\Connection\Connection;

class Smtp
{
    /**
     * Servidor SMTP
     * @var string
     */
    public $host;

    /**
     * Usuário SMTP
     * @var string
     */
    public $user;

    /**
     * Senha do usuário SMTP
     * @var string
     */
    public $pass;

    /**
     * Nome do usuário SMTP
     * @var string
     */
    public $name;

    /**
     * Tipo de autenticação SMTP
     * @var string ( SSL / TLS )
     */
    public $auth;

    /**
     * Porta SMTP
     * @var int|string ( 25 / 465 / 587 )
     */
    public $port;

    /**
     * Método construtor da classe
     */
    public function __construct()
    {
        $config_smtp = self::getSmtpConfig();

        $this->host = $config_smtp['host'];
        $this->user = $config_smtp['user'];
        $this->pass = $config_smtp['pass'];
        $this->name = $config_smtp['name'];
        $this->auth = $config_smtp['auth'];
        $this->port = $config_smtp['port'];
    }

    /**
     * Método responsável por obter as configurações SMTP salvas no banco de dados
     * @return array
     */
    public static function getSmtpConfig(): array
    {
        // Instância de conexão
        $db = new Connection('mw_smtp_config');

        // Query de consulta no banco
        $stmt = $db->select(
            " 
            `host`, 
            `user`, 
            `pass`,
            `name`, 
            `auth`, 
            `port`",
            null,
            "`id` DESC",
            1
        );

        // Array com as configurações
        $config_smtp = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Descriptografa a senha para apresentar ao usuário
        $config_smtp['pass'] = base64_decode($config_smtp['pass']);

        // Fecha conexão
        $db->closeConnection();

        // Retorna as configurações
        return $config_smtp;
    }

    /**
     * Método responsável por atualizar as configurações SMTP no banco de dados
     * @return bool
     */
    public static function updateSmtpConfig(array $values): bool
    {
        // Instância de conexão com o banco de dados
        $db = new Connection('mw_smtp_config');

        // Criptografa a senha do sender
        $values['pass'] = base64_encode($values['pass']);

        // Linhas afetadas pelo update
        $affected_rows = $db->update($values, '`id` = 1');

        // Retorna true se houver linhas afetadas, se não, retorna false
        return ($affected_rows > 0) ? true : false;
    }
}
