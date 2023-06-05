<?php

namespace App\Models\Registers;

use App\Models\Model;
use App\Connection\Connection;
use PDOException;
use PDOStatement;

class Table extends Model
{
    /**
     * Nome da tabela no banco
     * @var string
     */
    public string $table;

    /**
     * Url amigável da tabela
     * @var string
     */
    public string $slug;

    /**
     * Título da tabela em mw_menu
     * @var string
     */
    public string $title;

    /**
     * Ícone da tabela em mw_menu
     * @var string
     */
    public string $icon;

    /**
     * Array contendo as colunas dessa tabela
     * @var array
     */
    public array $columns;

    /**
     * Array contendo os campos dessa tabela
     * @var array
     */
    public array $fields;

    /**
     * Método construtor da classe
     * @param string $table
     * @param string $slug
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;
        $this->table = str_replace('-', '_', $this->slug);

        $db = new Connection('mw_menu');
        $res = $db->select("name, icon", "`url` = '{$this->slug}'")->fetch(\PDO::FETCH_ASSOC);

        $this->title = (isset($res['name'])) ? $res['name'] : 'Tabela não cadastrada em "Menu"';
        $this->icon =  (isset($res['icon'])) ? $res['icon'] : 'exclamation-triangle';

        $this->getTableFields();
        $this->getDatatableColumns();
    }

    /**
     * Método responsável por obter um array com o nome das colunas
     * @return void
     */
    private function getTableFields(): void
    {
        $db = new Connection($this->table);
        $stmt = $db->describe();

        $this->fields = [];
        while ($field = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $this->fields[] = new Field($this->table, $field['Field'], $field['Type'], $field['Null'], $field['Key'], $field['Default']);
        }
    }

    /**
     * Método responsável por retornar um array com o data e o title necessário para a DataTable
     * @return void
     */
    private function getDatatableColumns(): void
    {
        $this->columns[] = new Column(0, '<i class="bi bi-square" id="select-all"></i>', '1%', false, false);

        foreach ($this->fields as $field) {
            if (!in_array($field->field, NOT_PERM_COLS)) { // Verifica se a coluna não faz parte das colunas não permitida e adiciona a coluna no array de colunas
                $this->columns[] = new Column($field->field, $field->slug, $field, true, true);
            }
        }

        if ($this->table != MW_USERS_TABLE && $this->table != MW_RECIPIENTS_TABLE) { //Se a tabela não for a mw_users, push no array para coluna com botão ver
            $this->columns[] = new Column('see', $this->getTranslation('see'), '1%', false, false);
        }

        if ($this->table != MW_USERS_TABLE && !in_array($this->table, READONLY_TABLES)) { // Verifica se a tabela não faz parte das READONLY_TABLES e adiciona o botão de editar   
            $this->columns[] = new Column('edit', $this->getTranslation('edit'), '1%', false, false);
        }
    }

    /**
     * Método responsável por obter os dados de um registro
     * @param string|int $id
     * @param bool $is_view
     * @return array|false
     */
    public function getRegisterById(string|int $id, bool $is_view = false): array|false
    {
        $objRegister = new Register($this->slug, $id);
        return $objRegister->getRegisterData($is_view);
    }

    /**
     * Método responsável por obter os registros da tabela
     * @param array $get
     */
    public function getRegisters(array $get)
    {
        $db = new Connection($this->table);

        $search = (isset($get['search']['regex']) && $get['search']['regex']) ? $get['search']['value'] : 0; // Pesquisa da tabela
        $draw = (isset($get['draw']) && is_numeric($get['draw'])) ? $get['draw'] : 1;

        $start = (isset($get['start']) && is_numeric($get['start'])) ? $get['start'] : 0; // Inicio dos resultados por página

        $length = (isset($get['length']) && is_numeric($get['length'])) ? $get['length'] : 10; // Quantidade de resultados por página

        $col_num = (isset($get['order'][0]['column'])) ? $get['order'][0]['column'] : 0; // Número da coluna a ser ordenada
        $order_column = (isset($get['columns'][$col_num]['data'])) ? $get['columns'][$col_num]['data'] : 'id'; // Coluna a ser ordenada
        $order_method = (isset($get['order'][0]['dir'])) ? $get['order'][0]['dir'] : 'DESC'; // Método de ordenação

        $where = implode(" LIKE '%$search%' OR ", $this->getSearchableColumns($db->describe())) . " LIKE '%$search%'"; // Gera a query de consulta

        $num_rows = $db->select('*', $where, "`$order_column` $order_method")->rowCount(); // Query para obter o número total de registros

        $res = $db->select('*', $where, "$order_column $order_method", $length, $start); // Query para obter os registros da página atual

        $data = [];
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            $objRegister = new Register($this->slug);
            $data[] = $objRegister->generateRegisterRow($row);
        }

        $obj['draw'] = $draw;
        $obj['start'] = $start;
        $obj['length'] = $length;
        $obj['recordsTotal'] = $num_rows;
        $obj['recordsFiltered'] = $num_rows;
        $obj['data'] = $data;

        header('Content-Type: application/json');
        echo json_encode($obj);
        exit;
    }

    /**
     * Método responsável por obter apenas as colunas pesquisáveis e retorná-las como um array de strings:
     * ['`field`', '`field`']
     * @param PDOStatement $stmt
     * @return array
     */
    public function getSearchableColumns(PDOStatement $stmt): array
    {
        $columns = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (!in_array($row['Field'], NOT_SEARCHABLE_COLUMNS)) {
                $columns[] = "`{$row['Field']}`";
            }
        }
        return $columns;
    }

    /**
     * Método responsável por alterar o valor de um campo booleano da tabela
     * @param string $table
     * @param int|string $id
     * @param string $field
     * @param int $btn_val
     * @return bool
     */
    public static function activateBooleanField(string $table, int|string $id, string $field, int $btn_val): bool
    {
        // Instância de conexão com o banco de dados e update
        $affected_rows = (new Connection($table))->update([$field => $btn_val], "`id` = $id");

        // Retorna sucesso se alguma linha foi afetada
        return ($affected_rows > 0);
    }

    /**
     * Método responsável por inserir dados na tabela no banco de dados
     * @param array  $post
     * @param array  $files
     * @return int|PDOException
     */
    public function insert(array $post, array $files = []): int|PDOException
    {
        try {
            return (new Register($this->table))->insert($post, $files); // Last id
        } catch (PDOException $e) {
            return $e; // Retorna PDOException 
        }
    }

    public function deleteRegisters(array $ids): bool|PDOException
    {
        $deleted_registers = [];
        foreach ($ids as $id) {
            $objRegister = new Register($this->slug, $id);
            $affected_row = $objRegister->delete();
            if ($affected_row instanceof PDOException) {
                return $affected_row;
            }
            $deleted_registers[] = $objRegister->delete();
        }

        return !in_array(0, $deleted_registers);
    }
}
