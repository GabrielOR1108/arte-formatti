<?php

namespace App\Models\Users;

use App\Models\Model;
use App\Connection\Connection;
use App\Models\Registers\Registers;
use App\Models\File\File;
use App\Models\Registers\Column;
use App\Models\Registers\Field;
use App\Models\Registers\Register;
use App\Models\Registers\Table;
use PDOException;

class User extends Model
{
    /**
     * Identificador único do usuário
     * @var integer
     */
    public string $id;

    /**
     * Primeiro nome do usuário
     * @var string
     */
    public string $first_name;

    /**
     * Último nome do usuário
     * @var string
     */
    public string $last_name;

    /**
     * Email do usuário
     * @var string
     */
    public string $email;

    /**
     * Hash da senha do usuário
     * @var string
     */
    public string $password;

    /**
     * Nome do arquivo da imagem do usuário
     * @var string
     */
    public string $image;

    /**
     * Nível do usuário
     * @var int
     */
    public int $level;

    /**
     * Tabelas favoritas do usuário
     * @var array|null
     */
    public array|null $favorites;

    /**
     * Usuário ativo ou não
     * @var bool
     */
    public bool $active;

    /**
     * Método responsável por cadastrar um novo usuário no banco de dados
     * @return boolean
     */
    public function register(): bool
    {
        // Instancia conexão com banco de dados
        $db = new Connection(MW_USERS_TABLE);

        // Insere no banco de dados
        $this->id = $db->insert([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'image' => $this->image,
            'mw_user_level' => $this->level
        ]);

        // Sucesso
        return true;
    }

    /**
     * Método responsável por atualizar a senha de um usuário já cadastrado
     * @param string $hash
     * @return bool
     */
    public function changePass(string $hash): bool
    {
        // Instancia conexão com banco de dados
        $db = new Connection(MW_USERS_TABLE);

        // Atualiza a senha com a nova hash
        $affected_rows = $db->update(['password' => password_hash($this->password, PASSWORD_DEFAULT)], "`id` = {$this->id}");

        // Fecha a conexão com o banco
        $db->closeConnection();

        $Recover = Recover::getRecoverByHash($hash);

        return ($affected_rows & $Recover->consumeHash());
    }

    /**
     * Método responsável por buscar um usuário no banco de dados pelo email
     * @param int $id;
     * @return User|null
     */
    public static function getUserById(int $id): User | null
    {
        $db = new Connection(MW_USERS_TABLE);
        $stmt = $db->select("
            `id`, 
            `first_name`, 
            `last_name`, 
            `email`, 
            `password`,
            `image`, 
            `level`,
            `favorites`,
            `active`", "id = '$id'");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        $objUser = new User();

        $objUser->id            = $result['id'];
        $objUser->first_name    = $result['first_name'];
        $objUser->last_name     = $result['last_name'];
        $objUser->email         = $result['email'];
        $objUser->password      = $result['password'];
        $objUser->image         = $result['image'];
        $objUser->level         = $result['level'];
        $objUser->favorites     = json_decode($result['favorites'], true);
        $objUser->active        = $result['active'];

        $db->closeConnection();
        return $objUser;
    }

    /**
     * Método responsável por buscar um usuário no banco de dados pelo email
     * @param string $email;
     * @return User|null
     */
    public static function getUserByEmail(string $email): User | null
    {
        $db = new Connection(MW_USERS_TABLE);
        $stmt = $db->select("
            `id`, 
            `first_name`, 
            `last_name`, 
            `email`, 
            `password`,
            `image`, 
            `level`,
            `favorites`,
            `active`", "email = '$email'");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $objUser = new User();

        $objUser->id            = $result['id'];
        $objUser->first_name    = $result['first_name'];
        $objUser->last_name     = $result['last_name'];
        $objUser->email         = $result['email'];
        $objUser->password      = $result['password'];
        $objUser->image         = $result['image'];
        $objUser->level         = $result['level'];
        $objUser->favorites     = json_decode($result['favorites'], true);
        $objUser->active        = $result['active'];

        $db->closeConnection();
        return $objUser;
    }

    /**
     * Método responsável por atualizar as informações do perfil do usuário [nome, sobrenome, email, senha etc.]
     * @param array $values
     * @return bool
     */
    public function updateUser(array $values): bool
    {
        // Instância de conexão com o banco de dados
        $db = new Connection(MW_USERS_TABLE);

        // Linhas afetadas
        $affected_rows = $db->update($values, "`id` = '{$this->id}'");

        // Retorna true se houver linhas afetadas, se não, retorna false
        return ($affected_rows > 0) ? true : false;
    }

    /**
     * Método responsável por excluir um usuário do banco de dados
     * @return bool
     */
    public function deleteUser(): bool
    {
        $Register = new Register(str_replace('_', '-', MW_USERS_TABLE), $this->id);
        return $Register->delete();
    }

    /**
     * Método responsável por obter o nome do nível do usuário pelo id
     * @param int $level
     * @return string
     */
    public static function getLevelName(int $level): string
    {
        // Instância de conexão com o banco de dados
        $db = new Connection('mw_user_level');

        // Consulta no banco
        $res = $db->select('level', "id = $level")->fetch(\PDO::FETCH_ASSOC);

        // Retorna o nome do nível de usuário
        return $res['level'];
    }

    /**
     * Método responsável por verificar se o usuário é administrador
     * @param int $level
     * @return bool
     */
    public static function isAdmin(int $level): bool
    {
        return $level == ADMIN_LEVEL;
    }

    /**
     * Método responsável por inserir novo usuário no banco de dados
     * @return bool|PDOException
     */
    public static function createUser(array $post, array $files): bool|PDOException
    {
        // Instância do banco de dados
        $db = new Connection(MW_USERS_TABLE);

        // Array para insert
        $values = [];

        // Adiciona os dados de $post no array de values para inserir junto com files, caso existam
        foreach ($post as $field => $value) {
            $values[$field] = $value;
        }

        // Criptografa senha informada no cadastro
        $values['password'] = password_hash($values['password'], PASSWORD_DEFAULT);

        // Instância de um novo arquivo para imagem
        $image = new File($files['image']);

        // Adiciona o novo nome do arquivo no array values
        $values['image'] = $image->new_filename;

        // Para cada elemento de $files

        // Caminho de destino para arquivos
        $path =  dirname(__FILE__, 4) . "/uploads/mw_users/images";

        // Try catch para tratamento de erros
        try {
            // Verifica se houve o insert
            if ($db->insert($values)) {
                // Faz o upload do arquivo
                $image->upload($path, true);
            }
        } catch (PDOException $e) {
            return $e; // Retorna PDOException 
        }

        // Retorna sucesso
        return true;
    }

    /**
     * Método responsável por atualizar o avatar do usuário
     * @param array $image
     * @return string|false
     */
    public function updateAvatar(array $image): string|false
    {
        $Register = new Register(str_replace('_', '-', MW_USERS_TABLE), $this->id);

        $update = $Register->update([], ['image' => $image]);

        // Se não houver update retorna false
        if (!$update) {
            return false;
        }

        return $Register->getData()['image'];
    }

    /**
     * Método responsável por obter os níveis de usuários 
     * @return array
     */
    public static function getUserLevels(): array
    {
        // Array para retorno
        $levels = [];

        // Instância de conexão com o banco de dados
        $db = new Connection('mw_user_level');

        // Select no banco de dados
        $res = $db->select('id, level', 'active = 1', 'level ASC');

        while ($level = $res->fetch(\PDO::FETCH_ASSOC)) {
            $levels[] = $level;
        }

        // Retorna o array vazio ou preenchido com os níveis de usuário
        return $levels;
    }

    /**
     * Método responsável por atualizar os favoritos de um usuário
     * @param string $table
     * @return
     */
    public function updateFavorites(string $table)
    {
        // Verifica se os favoritos do User é um array
        if (!is_array($this->favorites)) {
            $this->favorites = [];
        }

        // Se a tabela já estiver nos favoritos, remove ela
        if (in_array($table, $this->favorites)) {
            $index = array_search($table, $this->favorites);
            unset($this->favorites[$index]);
        } else if (!in_array($table, $this->favorites)) {
            // Se a tabela não estiver nos favoritos, adiciona ela
            $this->favorites[] = $table;
        }

        // Instância de conexão com o banco de dados
        $db = new Connection(MW_USERS_TABLE);

        // JSON dos favoritos do usuário
        $json_favorites = json_encode($this->favorites, JSON_UNESCAPED_UNICODE);

        // Verifica se houve o update no banco de dados
        if ($db->update(['favorites' => $json_favorites], "id = {$this->id}") == 0) {
            return false;
        }

        // Atualiza a global $_SESSION com os novos favoritos
        $_SESSION['user']['favorites'] = $this->favorites;

        // retorna sucesso
        return true;
    }

    /**
     * Método responsável por retornar as colunas necessárias para a dataTable no formato de JSON
     * @return string
     */
    public function getData(): string
    {
        // Array com as colunas da tabela
        $fields = $this->getFields();

        // Obtém o array das colunas
        $columns = $this->generateColumns($fields);

        // Retorna as colunas necessárias para a dataTable no formato de JSON
        return json_encode($columns, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Método responsável por obter um array com o nome das colunas
     * @return array
     */
    private function getFields(): array
    {
        $db = new Connection(MW_USERS_TABLE);
        $stmt = $db->describe();

        $fields = [];
        while ($field = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $fields[] = new Field(MW_USERS_TABLE, $field['Field'], $field['Type'], $field['Null'], $field['Key'], $field['Default']);
        }

        // Retorna array com os fields
        return $fields;
    }

    /**
     * Método responsável por retornar um array com o data e o title necessário para a DataTable
     * @param array $fields
     * @return array
     */
    private function generateColumns(array $fields): array
    {
        $columns[] = new Column(0, '<i class="bi bi-square" id="select-all"></i>', '1%', false, false);

        foreach ($fields as $field) {
            if ($field->field == 'id') continue;

            if (!in_array($field->field, NOT_PERM_COLS)) { // Verifica se a coluna não faz parte das colunas não permitida e adiciona a coluna no array de colunas
                $columns[] = new Column($field->field, $field->slug, $field, true, true);
            }
        }
        return $columns;
    }

    /**
     * Método responsável por obter os registros da tabela
     */
    public static function getUsers($get)
    {
        // Instância de conexão com o banco de dados
        $db = new Connection(MW_USERS_TABLE);

        //Pesquisa da tabela
        $search = (isset($_GET['search']['regex']) && $_GET['search']['regex']) ? $_GET['search']['value'] : 0;

        $draw = (isset($_GET['draw']) && is_numeric($_GET['draw'])) ? $_GET['draw'] : 1;

        //Inicio dos resultados por página
        $start = (isset($_GET['start']) && is_numeric($_GET['start'])) ? $_GET['start'] : 0;

        //Quantidade de resultados por página
        $length = (isset($_GET['length']) && is_numeric($_GET['length'])) ? $_GET['length'] : 10;

        // Ordenação padrão das tabelas
        $order_column = 'id';
        $order_method = 'DESC';

        // Verifica se foi selecionado outra ordenação e altera $order_column e $order_method
        if (isset($get['order'][0]['column']) && is_numeric($get['order'][0]['column'])) {
            $col_num = $get['order'][0]['column'];
            $order_column = $get['columns'][$col_num]['data'];
            $order_method = $get['order'][0]['dir'];
        }
        // Obtém as colunas
        $res_columns = $db->describe();

        // Gera o "WHERE" da query
        $objTable = new Table(str_replace('_', '-', MW_USERS_TABLE));
        $where = implode(" LIKE '%$search%' OR ", $objTable->getSearchableColumns($db->describe())) . " LIKE '%$search%'";

        // Query para obter o número total de registros
        $num_rows = $db->select('*', $where, "`$order_column` $order_method")->rowCount();
        // Query para obter os registros da página atual
        $res = $db->select('*', $where, "`$order_column` $order_method", $length, $start);

        // Array para armazenar a data
        $data = [];

        // Fetch dos registros da página atual
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            // Adiciona a linha ao array $data
            $data[] = self::generateUserRow($row, MW_USERS_TABLE);
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
     */

    private static function generateUserRow(array $row, string $table)
    {
        // Percorre todo array da row
        foreach ($row as $field => $value) {
            // Adiciona coluna vazia na row para a checkbox que é renderizada dentro da view
            $row[] = '';

            switch ($field) {
                case "image":
                    $row[$field] = "<div class=\"d-flex\"><img class=\"img-fluid rounded-circle mx-auto\" style=\"max-width: 50px;\" src=\"uploads/$table/images/{$value}\"></div>";
                    break;
                case "level":
                    $levels = self::getUserLevels();
                    $options = '';
                    foreach ($levels as $level) {
                        $selected = ($value == $level['id']) ? 'selected' : '';
                        $options .= "<option value=\"{$level['id']}\" $selected>{$level['level']}</option>";
                    }
                    $row[$field] =
                        "<select name=\"level\" class=\"form-select w-100 my-auto level-select\">
                            $options
                        </select>";
                    break;
                case "last_login":
                    if ($value != null) {
                        $row[$field] = date('d/m/Y - H:i:s', strtotime($value));
                    }
                    break;
                case "active":
                    $icon = ($value == 1) ? 'check' : 'x';
                    $row[$field] = "<button class=\"btn btn-primary border btn-act\" data-field=\"{$field}\" value=\"{$value}\"><i class=\"bi bi-{$icon}-lg\"></i></button>";
                    break;
                default:
                    $row[$field] = $value;
                    break;
            }
        }

        // Retorna o array com a row
        return $row;
    }

    /**
     * Método responsável por atualizar o nível do usuário
     * @param int|string $level
     * @return bool
     */
    public function updateLevel(int|string $level): bool
    {
        // Instância de conexão com o banco de dados
        $db = new Connection(MW_USERS_TABLE);

        return $db->update(['level' => $level], "id = {$this->id}");
    }
}
