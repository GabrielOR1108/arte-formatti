<?php

namespace App\Models\Registers;

use App\Connection\Connection;

/**
 * Classe responsável por gerenciar um campo de um registro
 */
class Field extends Register
{
    /**
     * Nome do campo
     * @var string
     */
    public string $field;

    /**
     * Tipo do campo
     * @var string
     */
    public string $type;

    /**
     * Nulo ou não
     * @var bool
     */
    public bool $null;

    /**
     * Chave do campo
     * @var string|null
     */
    public string|null $key;

    /**
     * Valor padrão do campo
     * @var string|null
     */
    public string|null $default;

    /**
     * Tamanho do campo
     * @var string|null
     */
    public string|null $size;

    /**
     * Slug do campo
     * @var string
     */
    public string $slug;

    /**
     * Valor do campo
     * @var mixed
     */
    public mixed $value;

    /**
     * O campo é do tipo imagem
     * @var bool
     */
    public bool $image;

    /**
     * O campo é do tipo arquivo
     * @var bool
     */
    public bool $file;

    /**
     * O campo é do tipo galeria
     * @var bool
     */
    public bool $gallery;

    /**
     * O campo é do tipo chave estrangeira
     * @var bool
     */
    public bool $foreign;

    /**
     * O campo é de apenas leitura
     * @var bool
     */
    public bool $readonly;

    /**
     * O campo é de apenas leitura
     * @var string
     */
    public string $image_path;

    /**
     * O campo é de apenas leitura
     * @var string
     */
    public string $file_path;

    /**
     * Array contendo as informações 
     * necessárias para gerar o input
     * do campo na view
     * @var array
     */
    public array $input;

    public function __construct(string $slug, string $field, string $type, string $null, string|null $key, string|null $default)
    {
        $this->slug = $slug;
        $this->table = str_replace('-', '_', $this->slug);

        $this->field = $field;
        $this->type = explode('(', $type)[0];
        $this->null = ($null == 'YES');
        $this->key = $key;
        $this->default = $default;
        $this->size = $this->getStringBetween($type, '(', ')');
        $this->slug = $this->getTranslation($field);

        $this->image = $this->isImageField();
        $this->file = $this->isFileField();
        $this->gallery = $this->isGalleryField();
        $this->foreign = $this->isForeignKey();
        $this->readonly = $this->isReadonly();

        $this->image_path = dirname(__FILE__, 4) . "/uploads/{$this->table}/images";
        $this->file_path = dirname(__FILE__, 4) . "/uploads/{$this->table}/files";

        $this->getInput();
    }

    /**
     * Método responsável por verificar se o campo da tabela é um campo de imagem
     * @return bool
     */
    private function isImageField(): bool
    {
        return (array_key_exists($this->table, IMAGE_FIELDS) && array_key_exists($this->field, IMAGE_FIELDS[$this->table]));
    }

    /**
     * Método responsável por verificar se o campo da tabela é um campo de arquivo
     * @return bool
     */
    private function isFileField(): bool
    {
        return (array_key_exists($this->table, FILE_FIELDS) && in_array($this->field, FILE_FIELDS[$this->table]));
    }

    /**
     * Método responsável por verificar se o campo da tabela é um campo de imagem
     * @return bool
     */
    private function isGalleryField(): bool
    {
        return (array_key_exists($this->table, GALLERY_FIELDS) && in_array($this->field, GALLERY_FIELDS[$this->table]));
    }

    /**
     * Método responsável por verificar se o campo da tabela é uma foreign key
     * @return bool
     */
    private function isForeignKey(): bool
    {
        return (array_key_exists($this->table, FOREIGN_KEYS) && array_key_exists($this->field, FOREIGN_KEYS[$this->table]));
    }

    /**
     * Método responsável por verificar se o campo da tabela é um campo de arquivo
     * @return bool
     */
    private function isReadonly(): bool
    {
        return (array_key_exists($this->table, READONLY_FIELDS) && in_array($this->field, READONLY_FIELDS[$this->table]));
    }

    /**
     * Método responsável retornar o input do campo
     * @return void
     */
    public function getInput(): void
    {
        $input['name']  = $this->field; // Nome do campo para adicionar no atributo name do input
        $input['title'] = $this->getTranslation($this->field); // Título do campo para utilizar como label
        $input['required'] = (!$this->null) ? 'required' : ''; // O campo será obrigatório
        $input['readonly'] = ($this->readonly) ? 'readonly' : ''; // Verifica se o campo é apenas para leitura

        switch ($this->type) {
            case "boolean":
            case "tinyint":
                $input['type'] = "checkbox";
                break;
            case "enum":
                $input['type'] = "enum";
                $input['options'] = $this->getEnumOptions();
                break;
            case "int":
                $input['type'] = "int";
                if ($this->foreign) { // Verifica se o campo é uma foreign key e busca pelas opções para serem adicionadas ao select
                    $input['type'] = "foreign_key";
                    $input['options'] = $this->getForeignKeyOptions();
                }
                break;
            case "varchar":
                $input['type'] = "varchar";

                if ($this->image) {
                    $input['type'] = "image";
                    $input['dimensions'] = [
                        "width"  => IMAGE_FIELDS[$this->table][$this->field]['width'],
                        "height" => IMAGE_FIELDS[$this->table][$this->field]['height']
                    ];
                }

                if ($this->file) {
                    $input['type'] = 'file';
                }
                break;
            case "tinytext":
                $input['type'] = "tinytext";
                break;

            case "text":
            case "mediumtext":
                $input['type'] = "ckeditor";
                break;

            case "longtext":
            case "json":
                if ($this->gallery) {
                    $input['type'] = "gallery";
                }
                break;

            case "date":
                $input['type'] = "date";
                break;

            case "datetime":
                $input['type'] = "datetime";
                break;

            case "time":
                $input['type'] = "time";
                break;
        }

        $this->input = $input;
    }

    /**
     * Método responsável por obter as opções de uma foreign key
     * @param string $table
     * @param string $field
     * @return array
     */
    protected function getForeignKeyOptions()
    {
        // Coluna referenciada
        $ref_col = FOREIGN_KEYS[$this->table][$this->field]['referenced_col'];

        // Coluna para exibir como texto
        $field_text = FOREIGN_KEYS[$this->table][$this->field]['field_text'];

        // Instância de conexão com o banco de dados
        $db_fk = new Connection(FOREIGN_KEYS[$this->table][$this->field]['referenced_tbl']);

        // Consulta dos valores da tabela referenciada
        $res = $db_fk->select("$ref_col, $field_text", null, "$field_text ASC");

        // Array para armazenar as opções
        $options = [];

        if ($res->rowCount() < 1) {
            return [['value' => 0, 'text' => "Não existem registros cadastrados em: " . $this->getForeignKeyTitle(FOREIGN_KEYS[$this->table][$this->field]['referenced_tbl'], 'mw_menu', 'url', 'name')]];
        }

        // Se a tabela referenciada tiver registros cadastrados remove o disabled e adiciona as opções
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            $option['value'] = $row[$ref_col];
            $option['text']  = $row[$field_text];
            $options[] = $option;
        }

        $db_fk->closeConnection();

        return $options;
    }

    /**
     * Método responsável por obter as opções de um campo enum
     * 
     */
    protected function getEnumOptions()
    {
        $options_str = str_replace("'", '', $this->size);
        $options_ar = explode(',', $options_str);

        $options = [];
        foreach ($options_ar as $option) {
            $options[] = [
                'value' => $option,
                'text'  => $this->getTranslation($option)
            ];
        }
        return $options;
    }
}
