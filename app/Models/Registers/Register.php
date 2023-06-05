<?php

namespace App\Models\Registers;

use App\Connection\Connection;
use App\Models\File\File;
use App\Models\Gallery\Gallery;
use App\Models\Model;
use PDOException;

/**
 * Classe responsável por tratar cada registro do banco de dados
 */
class Register extends Table
{
    /**
     * id do registro
     * @var int|string|null
     */
    public int|string|null $id;

    public function __construct(string $slug, int|string|null $id = null)
    {
        parent::__construct($slug);
        $this->id = $id;
    }

    /**
     * Método responsável por retornar os dados
     * de um registro formatados para as DataTables
     * @param array $row
     */
    public function generateRegisterRow(array $row)
    {
        $db = new Connection($this->table);

        $columns = $db->describe()->fetchAll(\PDO::FETCH_ASSOC);
        $columns = array_combine(array_column($columns, 'Field'), $columns);

        foreach ($columns as $key => $column) {
            $columns[$key] = new Field($this->slug, $column['Field'], $column['Type'], $column['Null'], $column['Key'], $column['Default']);
        }

        foreach ($row as $field => $value) {
            // Adiciona coluna vazia na row para a checkbox que é renderizada dentro da view
            $row[] = '';

            // Adiciona apenas as colunas que não estão em NOT_PERM_COLS
            if (!in_array($field, NOT_PERM_COLS)) {
                $row[$field] = $value;
            }

            // Mensagem padrão caso o arquivo não exista
            $file_not_found = "<i class=\"bi bi-file-earmark-x file-not-found\"></i>";

            switch ($columns[$field]->type) {
                case "boolean":
                case "tinyint":
                    $icon = ($value == 1) ? 'check' : 'x';
                    $row[$field] = "<button class=\"btn btn-primary border btn-act\" data-field=\"{$field}\" value=\"{$row[$field]}\"><i class=\"bi bi-{$icon}-lg\"></i></button>";
                    break;
                case "enum":
                    $row[$field] = self::getTranslation($value);
                    break;
                case "varchar":
                    if ($field == 'icon') { // Ícones da tabela mw_menu
                        $row[$field] = "<i class=\"bi bi-{$row[$field]}\"></i>";
                    }

                    if ($columns[$field]->image) {
                        $image_path = $columns[$field]->image_path . "/{$row[$field]}";
                        $relative_path = "uploads/{$this->table}/images/{$row[$field]}";
                        $row[$field] = (file_exists($image_path) && is_file($image_path)) ? "<img class=\"img-display-table\" src=\"$relative_path\">" : $file_not_found;
                    }

                    if ($columns[$field]->file) {
                        $file_path = $columns[$field]->file_path . "/{$row[$field]}";
                        $relative_path = "uploads/{$this->table}/files/{$row[$field]}";
                        $row[$field] = (file_exists($file_path) && is_file($file_path)) ? "<a class=\"btn btn-outline-secondary btn-file-table\" href=\"$relative_path\"><i class=\"bi bi-file-earmark-arrow-down-fill\"></i></a>" : $file_not_found;
                    }

                    break;
                case "tinytext":
                case "text":
                case "mediumtext":
                case "longtext":
                    $row[$field] = self::truncate(strip_tags($row[$field]), 80);
                    break;

                case "date":
                    $row[$field] = date('d/m/Y', strtotime($row[$field]));
                    break;
                case "time":
                    $row[$field] = date('H:i:s', strtotime($row[$field]));
                    break;
                case "datetime":
                case "timestamp":
                    $row[$field] = date('d/m/Y - H:i:s', strtotime($row[$field]));
                    break;
            }

            // Verifica se a coluna é uma foreign key
            if ($columns[$field]->foreign) {
                $row[$field] = self::getForeignKeyTitle(
                    $row[$field],
                    FOREIGN_KEYS[$columns[$field]->table][$field]['referenced_tbl'],
                    FOREIGN_KEYS[$columns[$field]->table][$field]['referenced_col'],
                    FOREIGN_KEYS[$columns[$field]->table][$field]['field_text']
                );
            }
        }

        // Coluna "Ver"
        $row['see'] = "<a href=\"mw-admin/registers/{$this->slug}/see/{$row['id']}\" class=\"btn btn-primary border\"><i class=\"bi bi-eye\"></i></a>";

        // Verifica se a tabela está nas readonly_tables, caso não esteja, adiciona a coluna "Editar"
        if (!$columns[$field]->readonly) {
            $row['edit'] = "<a href=\"mw-admin/registers/{$this->slug}/edit/{$row['id']}\" class=\"btn btn-primary border\"><i class=\"bi bi-pencil-square\"></i></a>";
        }

        // Retorna o array com a row
        return $row;
    }

    /**
     * Método responsável por obter os inputs
     * @param bool $create
     * @return array
     */
    public function getInputs(bool $create = false): array
    {
        $db = new Connection($this->table);
        $columns = $db->describe();

        $inputs = [];
        foreach ($columns as $column) {
            if (in_array($column['Field'], NOT_PERM_FIELDS) || ($create && in_array($column['Field'], ONLY_SEE_FIELDS))) {
                continue;
            }

            $field = new Field($this->table, $column['Field'], $column['Type'], $column['Null'], $column['Key'], $column['Default']);
            $inputs[] = $field->input;
        }

        return $inputs;
    }

    /**
     * Obtém os dados do registro como array
     * @return array
     */
    public function getData(): array
    {
        $db = new Connection($this->table);
        return $db->select('*', "id = {$this->id}")->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Retorna os dados de um registro do banco
     * @param bool $is_view
     * @return array|false
     */
    public function getRegisterData(bool $is_view = false): array|false
    {
        $db = new Connection($this->table);
        $res = $db->select('*', "id = {$this->id}", 'id DESC', 1);
        return ($res->rowCount() == 1) ? self::removeOnlySeeFields($res->fetch(\PDO::FETCH_ASSOC), $is_view) : false;
    }

    /**
     * Método responsável por filtrar os campos de apenas leitura
     * @param array $row,
     * @param bool $is_view
     * @return array
     */
    private static function removeOnlySeeFields(array $row, bool $is_view): array
    {
        // Array de retorno com apenas os campos que não são de apenas leitura
        $values = [];

        // Foreach para remover as ONLY_SEE_FIELDS
        foreach ($row as $key => $value) {
            // Se $view for true ou se o campo não estiver nas colunas não permitidas no create, adiciona o valor
            if ($is_view || !in_array($key, ONLY_SEE_FIELDS)) {
                $values[$key] = $value;
            }
        }

        // Retorna os fields
        return $values;
    }

    /**
     * Método responsável por inserir dados na tabela no banco de dados
     * @param array  $post
     * @param array  $files
     * @return int|PDOException
     */
    public function insert(array $post, array $files = []): int|PDOException
    {
        $db = new Connection($this->table);

        $columns = $db->describe()->fetchAll(\PDO::FETCH_ASSOC);
        $columns = array_combine(array_column($columns, 'Field'), $columns);
        $columns = array_filter($columns, function ($value, $key) use ($post, $files) {
            return (isset($post[$key]) || isset($files[$key]));
        }, ARRAY_FILTER_USE_BOTH);

        $values = [];

        // Adiciona os dados de $post no array de values para inserir junto com files, caso existam
        foreach ($post as $field => $value) {
            $values[$field] = $value;
        }

        // Para cada elemento de $files
        foreach ($files as $key => $value) {
            $field = new Field($this->slug, $key, $columns[$key]['Type'], $columns[$key]['Null'], $columns[$key]['Key'], $columns[$key]['Default']);

            if ($field->gallery) {
                $gallery = new Gallery();
                $gallery->prepareImages($files[$key], $post[$key]);
                $gallery->uploadImages($field->image_path, compress_images: true);
                $values[$key] = $gallery->getGalleryAsJSON(); // Atribui a galeria como JSON para ser adicionado ao registro
            }

            if (!$field->gallery) {
                $file = new File($value);
                $values[$key] = $file->new_filename;
                $path = ($field->file) ? $field->file_path : $field->image_path;
                $file->upload($path, $field->image);
            }
        }

        try {
            return $db->insert($values); // Last id
        } catch (PDOException $e) {
            return $e; // Retorna PDOException 
        }
    }

    /**
     * Método responsável por atualizar os dados de um registro no banco de dados
     * @param array  $post
     * @param array  $files
     * @return int|PDOException
     */
    public function update(array $post = [], array $files = []): int|PDOException
    {
        // Instância de conexão com o banco de dados
        $db = new Connection($this->table);

        $columns = $db->describe()->fetchAll(\PDO::FETCH_ASSOC);
        $columns = array_combine(array_column($columns, 'Field'), $columns);
        $columns = array_filter($columns, function ($value, $key) use ($post, $files) {
            return (isset($post[$key]) || isset($files[$key]));
        }, ARRAY_FILTER_USE_BOTH);

        // Seleciona dados do registro
        $register_data = $db->select('*', "id = {$this->id}")->fetch(\PDO::FETCH_ASSOC);

        // Valores do registro
        foreach ($register_data as $field => $value) {
            $old_values[$field] = $value;
        }

        // Array para update
        $values = [];

        // Adiciona os dados de $post no array de values para inserir junto com files, caso existam
        foreach ($post as $field => $value) {
            $values[$field] = $value;
        }

        foreach ($files as $key => $value) {
            $field = new Field($this->slug, $key, $columns[$key]['Type'], $columns[$key]['Null'], $columns[$key]['Key'], $columns[$key]['Default']);

            if ($field->gallery) {
                $gallery = new Gallery($old_values[$field->field], $this->table, $field->field, $this->id);
                $gallery->prepareImages($files[$key], $post[$key]);
                $gallery->uploadImages($field->image_path, compress_images: true);
                $values[$key] = $gallery->getGalleryAsJSON(); // Atribui a galeria como JSON para ser adicionado ao registro
            }

            if (!$field->gallery) {
                $file = new File($value);
                $values[$key] = $file->new_filename;
                if ($field->image || $field->file) {
                    $this->deleteFiles($field->field, $old_values[$field->field]);
                }
                $path = ($field->file) ? $field->file_path : $field->image_path;
                $file->upload($path, $field->image);
            }
        }

        // Try catch para tratamento de erros
        try {
            return $db->update($values, "id = {$this->id}"); // Last id
        } catch (PDOException $e) {
            return $e; // Retorna PDOException 
        }
    }

    /**
     * Método responsável por excluir dados da tabela no banco de dados
     * @return int|PDOException
     */
    public function delete(): int|PDOException
    {
        // Instância de conexão com o banco de dados
        $db = new Connection($this->table);

        // Busca dados a serem excluidos
        $row = $db->select('*', "id = {$this->id}")->fetch(\PDO::FETCH_ASSOC);

        if (is_array($row)) {
            foreach ($row as $field => $value) {
                $this->deleteFiles($field, $value);
            }
        }

        try {
            return $db->delete("id = {$this->id}");
        } catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * Método responsável por verificar se o registro a ser excluído tem arquivos ou imagens para excluir
     * @param string $field
     * @param string|null $value
     * @return bool
     */
    public function deleteFiles(string $field, string|null $value): bool
    {
        $db = new Connection($this->table);
        $field_info = $db->describe($field)->fetch(\PDO::FETCH_ASSOC);

        $objField = new Field($this->slug, $field_info['Field'], $field_info['Type'], $field_info['Null'], $field_info['Key'], $field_info['Default']);

        if (!$objField->gallery && !$objField->image && $objField->file) {
            return true;
        }

        if ($objField->gallery) { // Verifica se campo é de galeria e excluí todas suas imagens
            $objGallery = new Gallery($value, $this->table, $objField->field);
            foreach ($objGallery->gallery as $key => $image) {
                $objGallery->deleteGalleryImage($this->table, $key);
            }
        }

        $file_path = ($objField->file) ? $objField->file_path : $objField->image_path;
        $file_path .= "/{$value}";

        return File::delete($file_path);
    }

    /**
     * Método responsável por gerar um array com as colunas e seus datatypes
     * @return array
     */
    public function getDataTypes(string $table): array
    {
        // Obtém os tipos de dados de cada coluna da tabela
        $conn = new Connection($table);
        $res = $conn->getDatatypes();
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            $datatypes[$row['COLUMN_NAME']] = $row['DATA_TYPE'];
        }

        // Retorna o array com ['COLUMN_NAME' => 'DATA_TYPE']
        return $datatypes;
    }
}
