<?php

namespace App\Models;

use App\Connection\Connection;

abstract class Model
{
    /**
     * Método responsável por obter uma substring entre dois caractéres
     * @param string $string
     * @param string $start
     * @param string $end
     * @return string
     */
    public function getStringBetween(string $string, string $start, string $end): string
    {
        $str = '';

        // Pega o conteúdo depois do $start
        $arr = explode($start, $string);

        // Verifica se havia alguma coisa depois do $start 
        if (isset($arr[1])) {
            // Pega o conteúdo depois do $start e antes do $end
            $arr = explode($end, $arr[1]);
            $str = $arr[0];
        }

        // Retorna string
        return $str;
    }

    /**
     * Método responsável por retornar uma string no tamanho específicado, como o data-length do html
     * @param string $string
     * @param int $length
     * @return string
     */
    public static function truncate(string $string, int $length): string
    {
        // Remove tags HTML e PHP
        $string = strip_tags($string);

        // Verifica se a string é maior que a length informada
        if (strlen($string) > $length) {
            $string = substr($string, 0, ($length - 3));
            $string = substr($string, 0, strrpos($string, ' ')) . '...';
        }

        // Retorna a string
        return $string;
    }


    /**
     * Método responsável por verificar se o campo é uma foreign key
     * @param string $table
     * @param string $field
     * @return bool
     */
    protected static function verifyForeignKey(string $table, string $field): bool
    {
        return array_key_exists($table, FOREIGN_KEYS) && array_key_exists($field, FOREIGN_KEYS[$table]);
    }

    /**
     * Método responsável por obter uma tradução para a foreign key
     * @param int|string $fk
     * @param string     $field
     * @param string     $referenced_tbl
     * @param string     $referenced_col
     * @return string
     */
    protected static function getForeignKeyTitle(int|string $fk, string $referenced_tbl, string $referenced_col, string $field_text): string
    {
        // Instância de conexão com o banco de dados
        $db = new Connection($referenced_tbl);

        // Executa select
        $res = $db->select($field_text, "$referenced_col = '$fk'");

        // Variável para return
        $return = '?';

        if ($res->rowCount() > 0) {
            $res = $res->fetch(\PDO::FETCH_ASSOC);
            $return = $res[$field_text];
        }

        return $return;
    }

    /**
     * Método responsável por verificar se uma tabela existe
     * @param string $table
     * @return bool
     */
    public static function tableExists(string $table): bool
    {
        return (new Connection($table))->tableExists();
    }

    /**
     * Método responsável por obter os os grupos e menus do sistema
     */
    public static function getSystemMenu(bool $admin)
    {
        // Instância de conexão com o banco de dados
        $db = new Connection('mw_group_menu');

        // Busca os grupos no banco de dados
        $res = $db->select('id, name, icon', 'active = 1', "priority DESC, id ASC");

        // Array para armazenar os grupos
        $groups = [];

        // Popula o array groups com os grupos encontrados no banco
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            $groups[$row['id']] = $row;
        }

        // Fecha a conexão com o banco de dados
        $db->closeConnection();

        // Instância de conexão com o banco de dados
        $db = new Connection('mw_menu');

        // Busca os grupos no banco de dados
        $res = $db->select("name, mw_group_menu, icon, url", 'active = 1', 'priority DESC, id ASC');

        // Popula o array de cada grupo com os menus pertencentes 
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            if ($admin) {
                $groups[$row['mw_group_menu']]['menus'][] = $row;
            }

            if (!$admin && in_array($row['url'], COMMON_USER_TABLES)) {
                $groups[$row['mw_group_menu']]['menus'][] = $row;
            }
        }

        // Fecha a conexão com o banco de dados
        $db->closeConnection();

        // Retorna o array populado ou vazio
        return $groups;
    }

    /**
     * Método responsável por obter o menu dos favoritos do usuário
     * @param array|null $favorites
     * @return array
     */
    public static function getFavoritesMenu(array|null $favorites): array
    {
        // Array para armazenar os menus
        $favorites_menus = [];

        // Se $favorites for null retorna array vazio
        if (is_null($favorites)) return $favorites_menus;

        // Instância de conexão com o banco de dados
        $db = new Connection('mw_menu');

        // Consulta no banco de dados
        $res = $db->select('name, icon, url', "active = '1' AND url = '" . implode("' OR url = '", $favorites) . "'", 'name ASC');

        // Adiciona cada menu nos favoritos
        while ($menu = $res->fetch(\PDO::FETCH_ASSOC)) {
            $favorites_menus[] = $menu;
        }

        // Retorna o menu dos favoritos
        return $favorites_menus;
    }

    /**
     * Método responsável por obter a tradução de uma coluna
     * @param string $field
     * @return string
     */
    protected function getTranslation(string $field): string
    {
        return array_key_exists($field, LANG_PTBR) ? LANG_PTBR[$field] : $field;
    }
}
