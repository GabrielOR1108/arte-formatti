<?php

namespace App\Models\Registers;

use App\Models\Model;

class Column extends Model
{
    public string|int $data;

    public string $title;

    public string $width;

    public bool $orderable;

    public bool $searchable;


    public function __construct(string|int $data, string $title, string|Field $width, bool $orderable, bool $searchable)
    {
        $this->data = $data;
        $this->title = $title;

        if (is_string($width)) {
            $this->width = $width;
        } else if ($width instanceof Field) {
            $this->width = $this->getWidthByField($width);
        }

        $this->orderable = $orderable;
        $this->searchable = $searchable;
    }

    /**
     * Método responsável por obter a largura da coluna
     * @param Field $field
     * @return string
     */
    public function getWidthByField(Field $field): string
    {
        $width = '5%'; // Tamanho padrão 

        // Switch tipo dos campos
        switch ($field->type) {
            case "tinyint":
            case "boolean":
                $width = '1%';
                break;
        }

        // Switch pelo campo
        switch ($field->field) {
            case "id":
                $width = '4%';
                break;
        }

        // Retorna largura da coluna
        return $width;
    }
}
