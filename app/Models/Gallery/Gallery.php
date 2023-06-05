<?php

namespace App\Models\Gallery;

use App\Models\Model;
use App\Connection\Connection;
use App\Models\File\File;

class Gallery extends Model
{
    /**
     * Array o $_FILE de cada imagem
     * 
     * @var array
     */
    public array $image_files;

    /**
     * Array contendo o nome e o apelido das imagens da galeria
     * 
     * @var array
     */
    public array $gallery;

    /**
     * Último indice do array gallery para update
     * 
     * @var array
     */
    public int $last_index;

    public string $table;

    public string $field;

    public int|string $id;

    public function __construct(string $json = null, string $table = null, string $field = null, int|string $id = null)
    {
        if ($json) {
            $this->gallery = json_decode($json, true);
        }

        if ($table) {
            $this->table = $table;
        }

        if ($field) {
            $this->field = $field;
        }

        if ($id) {
            $this->id = $id;
        }

        $this->last_index = (isset($this->gallery) && array_key_last($this->gallery)) ? array_key_last($this->gallery) : 0;
    }

    /**
     * Método responsável por separar os $_FILES da galeria em objetos únicos
     * 
     * @param array $files $_FILES da galeria
     * @param array $names $_POST com os apelidos das imagens da galeria
     */
    public function prepareImages(array $files, array $names)
    {
        for ($i = 0; $i < sizeof($files['name']); $i++) {
            $this->image_files[] = [
                "name"      => $files['name'][$i],
                "full_path" => $files['full_path'][$i],
                "type"      => $files['type'][$i],
                "tmp_name"  => $files['tmp_name'][$i],
                "error"     => $files['error'][$i],
                "size"      => $files['size'][$i],
            ];

            $this->gallery[] = [
                'name' => $names[$i],
            ];
        }
    }


    /**
     * Método responsável por fazer o upload das imagens que estão na galeria
     * 
     * @param string $path
     * @return bool
     */
    public function uploadImages(string $path, bool $compress_images): bool
    {
        $errors = [];
        $database_gallery = $this->getGalleryInDatabase();

        if (empty($database_gallery)) {
            foreach ($this->image_files as $key => $image_file) {
                $file = new File($image_file);

                $this->gallery[$key]['src'] = $file->new_filename;

                $errors[] = $file->upload($path, $compress_images);
            }
        }

        if (!empty($database_gallery) && !is_null($this->last_index)) {
            foreach ($this->image_files as $key => $image_file) {
                $file = new File($image_file);

                $this->gallery[($this->last_index + $key) + 1]['src'] = $file->new_filename;

                $errors[] = $file->upload($path, $compress_images);
            }
        }

        return !in_array(false, $errors);
    }

    public function getGalleryAsJSON(): string
    {
        return json_encode($this->gallery, JSON_FORCE_OBJECT);
    }

    /**
     * Método responsável por alterar o valor da galeria em um registro
     * @param string $table
     * @param int|string $id
     * @param array $values
     * @return bool
     */
    public function updateGalleryImageName(array $values): bool
    {
        // Instância de conexão com o banco de dados e update
        $affected_rows = (new Connection($this->table))->update($values, "`id` = {$this->id}");

        // Retorna sucesso se alguma linha foi afetada
        return ($affected_rows > 0);
    }

    /**
     * Método responsável por excluír uma imagem da galeria
     * 
     * @param string $table
     * @param int $index
     * @return bool
     */
    public function deleteGalleryImage(string $table, int $index): bool
    {
        $file_path = dirname(__FILE__, 4) . "/uploads/$table/images/" . $this->gallery[$index]['src'];
        unset($this->gallery[$index]);
        return File::delete($file_path);
    }

    /**
     * Método responsável por obter a galeria que está registrada no banco de dados
     */
    public function getGalleryInDatabase()
    {
        if (!isset($this->table) || !isset($this->field) || !isset($this->id)) {
            return [];
        }

        $db = new Connection($this->table);
        $res = $db->select("{$this->field}", "id = {$this->id}");
        $gallery = $res->fetch(\PDO::FETCH_ASSOC);
        $db->closeConnection();
        return json_decode($gallery[$this->field], true);
    }
}
