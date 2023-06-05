<?php

namespace App\Models\Recipients;

use App\Models\Model;
use App\Connection\Connection;
use App\Models\Registers\Column;
use App\Models\Registers\Field;
use App\Models\Registers\Register;
use App\Models\Registers\Table;
use PDOException;

class Recipients extends Model
{
    /**
     * Tabela responsável no banco de dados
     * @var string
     */
    private string $table = MW_RECIPIENTS_TABLE;

    /**
     * Chave identificadora do destinatário
     * @var int|string
     */
    public int|string $id;

    /**
     * Nome do destinarário
     * @var string
     */
    public string $name;

    /**
     * Email do destinarário
     * @var string
     */
    public string $email;

    /**
     * Método construtor da classe
     */
    public function __construct()
    {
    }

    /**
     * Método responsável por retornar as colunas necessárias para a dataTable no formato de JSON
     * @return string
     */
    public function getData(): string
    {
        // Array com as colunas da tabela
        $fields = $this->getTableFields();

        // Obtém o array das colunas
        $columns = $this->getDatatableColumns($fields);

        // Retorna as colunas necessárias para a dataTable no formato de JSON
        return json_encode($columns, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Método responsável por obter um array com o nome das colunas
     * @return array
     */
    private function getTableFields(): array
    {
        $db = new Connection($this->table);
        $stmt = $db->describe();

        $fields = [];
        while ($field = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $fields[] = new Field($this->table, $field['Field'], $field['Type'], $field['Null'], $field['Key'], $field['Default']);
        }

        return $fields;
    }

    /**
     * Método responsável por retornar um array com o data e o title necessário para a DataTable
     * @return array
     */
    private function getDatatableColumns($fields): array
    {
        $columns = [];
        foreach ($fields as $field) {
            if (!in_array($field->field, NOT_PERM_COLS)) { // Verifica se a coluna não faz parte das colunas não permitida e adiciona a coluna no array de colunas
                $columns[] = new Column($field->field, $field->slug, $field, true, true);
            }
        }

        $columns[] = ["data" => 'edit', "title" => $this->getTranslation('edit'), 'width' => '1%', 'orderable' => false];
        return $columns;
    }

    /**
     * Método responsável por obter os dados de um registro
     * @param int|string $id
     * @return Recipients|false
     */
    public static function getRecipientById(int|string $id): Recipients|false
    {
        // Instância de conexão com o banco de dados
        $db = new Connection(MW_RECIPIENTS_TABLE);

        $stmt = $db->select('id, name, email', "id = $id", 'id DESC', 1);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, self::class);

        // Select no banco de dados
        $objRecipient = $stmt->fetch();

        return $objRecipient;
    }

    /**
     * Método responsável por obter os registros da tabela
     */
    public function getRecipients($get)
    {
        // Instância de conexão com o banco de dados
        $db = new Connection(MW_RECIPIENTS_TABLE);

        //Pesquisa da tabela
        (isset($get['search']['regex']) && $get['search']['regex']) ? $search = $get['search']['value'] : 0;

        (isset($get['draw']) && is_numeric($get['draw'])) ? $draw = $get['draw'] : $draw = 1;

        //Inicio dos resultados por página
        (isset($get['start']) && is_numeric($get['start'])) ? $start = $get['start'] : $start = 0;

        //Quantidade de resultados por página
        (isset($get['length']) && is_numeric($get['length'])) ? $length = $get['length'] : $length = 10;

        // Ordenação padrão das tabelas
        $order_column = 'id';
        $order_method = 'DESC';

        // Verifica se foi selecionado outra ordenação e altera $order_column e $order_method
        if (isset($get['order'][0]['column']) && is_numeric($get['order'][0]['column'])) {
            $col_num = $get['order'][0]['column'];
            $order_column = $get['columns'][$col_num]['data'];
            $order_method = $get['order'][0]['dir'];
        }

        // Gera o "WHERE" da query
        $where = "name LIKE '%$search%' OR email LIKE '%$search%'";

        // Query para obter o número total de registros
        $num_rows = $db->select('*', $where, "`$order_column` $order_method")->rowCount();
        // Query para obter os registros da página atual
        $res = $db->select('*', $where, "`$order_column` $order_method", $length, $start);

        // Array para armazenar a data
        $data = [];

        // Fetch dos registros da página atual
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            // Adiciona a linha ao array $data
            $data[] = $this->generateRecipientRow($row);
        }

        $obj['draw'] = $draw;
        $obj['start'] = $start;
        $obj['length'] = $length;
        $obj['recordsTotal'] = $num_rows;
        $obj['recordsFiltered'] = $num_rows;
        $obj['data'] = $data;

        // Retorna JSON COM a data
        header('Content-Type: application/json');
        echo json_encode($obj);
        exit;
    }

    /**
     * Método responsável por remover os dados das colunas não permitidas em NOT_PERM_TABLES
     * @param array $row
     * @return array
     */

    private function generateRecipientRow(array $row): array
    {
        $objRegister = new Register(str_replace('_', '-', $this->table));
        $datatypes = $objRegister->getDataTypes($objRegister->table);

        foreach ($row as $field => $value) {
            $row[] = ''; // Adiciona coluna vazia na row para a checkbox que é renderizada dentro da view

            if (!in_array($field, NOT_PERM_COLS)) { // Adiciona apenas as colunas que não estão em NOT_PERM_COLS
                $row[$field] = $value;
            }

            $datatype = $datatypes[$field];
            if ($datatype == "boolean" || $datatype == "tinyint") {
                $icon = ($value == 1) ? 'check' : 'x';
                $row[$field] = "<button class=\"btn btn-primary border btn-act\" data-field=\"{$field}\" value=\"{$row[$field]}\"><i class=\"bi bi-{$icon}-lg\"></i></button>";
            }
        }

        $row['edit'] = "<button data-id=\"{$row['id']}\" class=\"btn btn-primary border edit-recipient\"><i class=\"bi bi-pencil-square\"></i></button>";

        // Retorna o array com a row
        return $row;
    }

    /**
     * Método responsável por inserir dados na tabela no banco de dados
     * @param array  $post
     * @param array  $files
     * @return int|PDOException
     */
    public static function insert(array $post): int|PDOException
    {
        // Instância de conexão com o banco de dados
        $db = new Connection(MW_RECIPIENTS_TABLE);

        // Try catch para tratamento de erros
        try {
            return $db->insert($post); // Last id
        } catch (PDOException $e) {
            return $e; // Retorna PDOException 
        }
    }

    /**
     * Método responsável por atualizar os dados de um registro no banco de dados
     * @param int|string $id,
     * @param array  $post
     * @param array  $files
     * @return int|PDOException
     */
    public static function update(int|string $id, array $post = []): int|PDOException
    {
        // Instância de conexão com o banco de dados
        $db = new Connection(MW_RECIPIENTS_TABLE);

        // Try catch para tratamento de erros
        try {
            return $db->update($post, "id = $id"); // Last id
        } catch (PDOException $e) {
            return $e; // Retorna PDOException 
        }
    }

    /**
     * Método responsável por excluir dados da tabela no banco de dados
     * @param array $ids
     * @return int|PDOException
     */
    public static function delete(array $ids): int|PDOException
    {
        // Instância de conexão com o banco de dados
        $db = new Connection(MW_RECIPIENTS_TABLE);

        try {
            // Retorna linhas afetadas
            return $db->delete("id = " . implode(' OR id = ', $ids));
        } catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * Método responsável por obter os destinatários como um array para envio de emails
     * 
     * @param string $form_name
     * @return array
     */
    public static function getRecipientsEmailsByForm(String $form_name): array
    {
        // Instância de conexão com o banco de dados
        $db = new Connection(MW_RECIPIENTS_TABLE);

        // Select no banco de dados
        $res = $db->select('email', "$form_name = 1");

        // Array dos destinatários encontrados
        $recipients = [];
        while ($recipient = $res->fetch(\PDO::FETCH_ASSOC)) {
            $recipients[] = $recipient['email'];
        }

        return $recipients;
    }
}
