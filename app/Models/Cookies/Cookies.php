<?php

namespace App\Models\Cookies;

use App\Connection\Connection;

class Cookies
{
    /**
     * Tabela no banco que armazena as informações dos cookies
     * @var string
     */
    private string $table = 'mw_cookies';

    /**
     * Título do modal
     * @var string
     */
    public string $title;

    /**
     * Texto do modal
     * @var string
     */
    public string $text;

    /**
     * Método construtor da classe
     */
    public function __construct()
    {
        $this->getInfo();
    }

    /**
     * Método responsável por obter as 
     * informaçõesdo modal salvas no banco
     * @return void
     */
    public function getInfo(): void
    {
        $db = new Connection($this->table);
        $info = $db->select("`title`, `text`", null, "`id` DESC", 1)->fetch(\PDO::FETCH_ASSOC);
        $this->title = $info['title'];
        $this->text = $info['text'];
        $db->closeConnection();
    }

    /**
     * Método responsável por atualizar o título
     * e o texto do modal de cookies no banco de dados
     * @return bool
     */
    public function updateInfo(array $values): bool
    {
        $db = new Connection($this->table);
        $affected_rows = $db->update($values, '`id` = 1');
        return $affected_rows > 0;
    }

    /**
     * Retorna o objeto no formato de array
     * @return array
     */
    public function toArray(): array
    {
        return ['title' => $this->title, 'text' => $this->text];
    }
}
