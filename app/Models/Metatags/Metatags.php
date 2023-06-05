<?php

namespace App\Models\Metatags;

use App\Connection\Connection;

class Metatags
{
    /**
     * Meta description
     * @var string
     */
    public $description;

    /**
     * Meta keywords
     * @var string
     */
    public $keywords;

    /**
     * Método construtor da classe
     */
    public function __construct()
    {
        $info = self::getMetatags();

        $this->description = $info['description'];
        $this->keywords  = $info['keywords'];
    }

    /**
     * Método responsável por obter as metatags do banco de dados
     * @return array
     */
    public static function getMetatags(): array
    {
        // Instância de conexão com o banco de dados
        $db = new Connection('mw_metatags');

        // Query de consulta no banco de dados
        $stmt = $db->select("`description`, `keywords`", null, "`id` DESC", 1);

        // Array com as metatags
        $metatags = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Fecha conexão
        $db->closeConnection();

        // Retorna as metatags
        return $metatags;
    }

    /**
     * Método responsável por atualizar as metatags no banco de dados
     * @return bool
     */
    public static function updateMetatags(array $values): bool
    {
        // Instância de conexão com o banco de dados
        $db = new Connection('mw_metatags');

        // Linhas afetadas pelo update
        $affected_rows = $db->update($values, '`id` = 1');

        // Retorna true se houver linhas afetadas, se não, retorna false
        return ($affected_rows > 0) ? true : false;
    }
}
