<?php

namespace App\Connection;

use PDO;
use PDOException;
use PDOStatement;

class Connection
{
    /**
     * Host de conexão com o banco de dados
     * @var string
     */
    const HOSTNAME = DB_HOSTNAME;

    /**
     * Nome do banco de dados
     * @var string
     */
    const DATABASE = DB_DATABASE;

    /**
     * Usuário do banco de dados
     * @var string
     */
    const USERNAME = DB_USERNAME;

    /**
     * Senha de acesso do banco de dados
     * @var string
     */
    const PASSWORD = DB_PASSWORD;

    /**
     * Nome da tabela a ser manipulada
     * @var string
     */
    private $table;

    /**
     * Instância de conexão com o banco de dados
     * @var PDO
     */
    private $connection;

    /**
     * Método construtor da classe Database
     * @param string $table
     * @return void
     */
    public function __construct(string $table)
    {
        $this->setConnection();
        $this->table = $table;
    }

    /**
     * Método responsável por criar a conexão com o banco de dados
     * @return void
     */
    private function setConnection(): void
    {
        try {
            $this->connection = new PDO('mysql:host=' . self::HOSTNAME . ';dbname=' . self::DATABASE, self::USERNAME, self::PASSWORD);
        } catch (PDOException $e) {
            throw new PDOException("Error while trying to connect to db. {$e->getCode()}: {$e->getMessage()}");
        }
    }

    /**
     * Método responsável por fechar a conexão com o banco de dados
     * @return void
     */
    public function closeConnection(): void
    {
        $this->connection = null;
    }

    /**
     * Método responsável por executar querys dentro do banco de dados
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public function execute(string $query, $params = []): PDOStatement
    {
        try {
            // Prepara query
            $statement = $this->connection->prepare($query);

            // Binda os parametros
            $statement->execute($params);
        } catch (PDOException $e) {
            $this->throwMySQLException($e);
        }

        // Retorna o statement
        return $statement;
    }

    /**
     * Método responsável por tratar as PDOException
     * @param PDOException $e
     */
    public function throwMySQLException(PDOException $e)
    {
        // Foreign keys
        if ($e->getCode() == "1451") {
            $new_message = "Não é possível excluir esse(s) registro(s) pois existem registros relacionados à ele(s).";
            throw new PDOException($new_message, $e->getCode());
        }

        // Duplicate entries
        if ($e->getCode() == "1062") {
            $new_message = str_replace(['Duplicate entry', 'for key'], ['Já existe um registro com o valor', 'no campo'], $e->getMessage());
            throw new PDOException($new_message, $e->getCode());
        }

        // Any
        throw new PDOException("Error while trying to execute query. {$e->getCode()}: {$e->getMessage()}", (int) $e->getCode());
    }

    /**
     * Método responsável por inserir um registro no banco de dados
     * @param array $values [ field => value ]
     * @return int last id
     */
    public function insert(array $values): int
    {
        // Dados da query
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');

        // Monta a query
        $query = "INSERT INTO `{$this->table}` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $binds) . ");";

        // Executa insert
        $this->execute($query, array_values($values));

        // Retorna último id cadastrado
        return $this->connection->lastInsertId();
    }

    /**
     * Método responsável por fazer uma consulta no banco de dados
     * @param string $fields
     * @param string $where
     * @param string $order
     * @param int|string $limit
     * @param int|string $offset
     * @return PDOStatement
     */
    public function select(string $fields = '*', string $where = null, string $order = null, int|string $limit = null, int|string $offset = null): PDOStatement
    {
        //Dados da query
        $where  = strlen($where)  ? "WHERE $where" : '';
        $order  = strlen($order)  ? "ORDER BY $order" : '';
        $limit  = strlen($limit)  ? "LIMIT $limit" : '';
        $offset = strlen($offset) ? "OFFSET $offset" : '';

        // Monta a query
        $query = "SELECT $fields FROM {$this->table} $where $order $limit $offset";

        // Executa a query
        return $this->execute($query);
    }

    /**
     * Método responsável por executar atualizações no banco de dados
     * @param array $values
     * @param string $where
     * @return int
     */
    public function update(array $values, string $where): int
    {
        // Dados da query
        $fields = array_keys($values);

        // Monta a query
        $query = "UPDATE {$this->table} SET " . implode('=?, ', $fields) . "=? WHERE $where";

        // Executa a query e retorna linhas afetadas
        return $this->execute($query, array_values($values))->rowCount();
    }

    /**
     * Método responsável por excluir dados do banco de dados
     * @param string $where
     * @return int
     */
    public function delete($where): int
    {
        // Monta a query
        $query = "DELETE FROM {$this->table} WHERE $where;";

        // Retorna sucesso
        return $this->execute($query)->rowCount();
    }

    /**
     * Método responsável por retornar as colunas de uma tabela
     * @param string|null $field
     * @return PDOStatement
     */
    public function describe(string|null $field = null): PDOStatement
    {
        // Monta a query
        $query = "DESCRIBE {$this->table} $field";

        // Retorna o resultado
        return $this->execute($query);
    }

    /**
     * Método responsável por verificar se tabela existe no banco de dados
     * @return bool
     */
    public function tableExists(): bool
    {
        // Monta query
        $query = "SELECT `url` FROM `mw_menu` WHERE `url` = '{$this->table}';";

        // Executa query e salva o resultado
        $res = $this->execute($query);

        // Retorna true se a quantidade de linhas do resultado for maior que 0
        return $res->rowCount() > 0;
    }

    /**
     * Método responsável por obter os datatypes das colunas de uma tabela no banco de dados
     * @return PDOStatement
     */
    public function getDatatypes(): PDOStatement
    {
        // Monta a query
        $query = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->table}'";

        // Retorna o resultado
        return $this->execute($query);
    }
}
