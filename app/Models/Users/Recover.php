<?php

namespace App\Models\Users;

use App\Models\Model;
use App\Connection\Connection;

class Recover extends Model
{
    /**
     * Chave identificadora de mw_recoveries
     * @var int
     */
    public int $id;

    /**
     * Email do usuário que requisitou o reset de senha
     * @var string
     */
    public string $email;

    /**
     * Hash gerada na hora da requisição da recuperação de senha
     * @var string
     */
    public string $hash;

    /**
     * Bool que guarda se a chave já foi utilizada ou não (1|0)
     * @var bool
     */
    public bool $status;

    /**
     * Data e hora do momento em que foi requisitado a recuperação de senha
     * @var string
     */
    public string $requested_at;

    /**
     * Data e hora do momento em que foi usada a recuperação de senha
     * @var string|null
     */
    public string|null $used_at;

    /**
     * Método responsável por obter os dados de uma recoverie e associá-los à classe
     * @param string $hash
     * @return Recover|null
     */
    public static function getRecoverByHash(string $hash): Recover|null
    {
        // Instancia do banco de dados
        $db = new Connection('mw_recoveries');

        // Select no banco de dados
        $stmt = $db->select(
            "`id`, `email`, `hash`, `status`, `requested_at`,`used_at`",
            "hash = '$hash' AND `status` = '0' AND `requested_at` >= NOW() - INTERVAL 30 MINUTE",
            "`id` DESC",
            "1"
        );

        // Gera um objeto da classe Recover
        $stmt->setFetchMode(\PDO::FETCH_CLASS, self::class);
        $Recover = $stmt->fetch();

        // Fecha conexão com o banco
        $db->closeConnection();

        // Retorna o objeto ou null
        return $Recover;
    }

    /**
     * Método responsável por gerar a hash de uma recuperação de senha e a inserir no banco
     * @return string|false
     */

    public static function generateHash(string $email): string|false
    {
        // Gera a hash
        $hash = md5(uniqid(time(), true));

        // Instância de conexão com o banco de dados
        $db = new Connection('mw_recoveries');

        // Insere hash no banco de dados
        $insert = $db->insert([
            'email' => $email,
            'hash'  => $hash
        ]);

        // Retorna a hash ou false
        return ($insert != 0) ? $hash : false;
    }

    /**
     * Método responsável por consumir a hash de uma recuperação de senha
     * @return bool
     */
    public function consumeHash(): bool
    {
        // Instancia de conexão com o banco de dados
        $db = new Connection('mw_recoveries');

        // Atualiza o status da hash de 0 para 1
        $affected_rows = $db->update(['status' => 1], "`hash` = '{$this->hash}'");

        // Valida se houve linhas afetadas no banco e retorna o sucesso da operação
        return $affected_rows;
    }
}
