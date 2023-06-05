<?php

namespace App\Models\File;

use App\Models\Model;

class File extends Model
{
    /**
     * Nome do arquivo (sem extensão)
     * @var string
     */
    private string $name;

    /**
     * Extensão do arquivo (sem ponto)
     * @var string
     */
    private string $extension;

    /**
     * Novo basename do arquivo
     * @var string
     */
    public string $new_filename;

    /**
     * Type do arquivo
     * @var string
     */
    private string $type;

    /**
     * Caminho temporário do arquivo
     * @var string
     */
    private string $tmp_name;

    /**
     * Código de erro do upload
     * @var int
     */
    private int $error;

    /**
     * Tamanho do arquivo
     * @var int
     */
    private int $size;

    /**
     * Método construtor da classe
     * @param array $file = $_FILES['campo']
     */
    public function __construct(array $file)
    {
        $info = pathinfo($file['name']);

        $this->name         = $info['filename'];

        $this->type         = $file['type'];
        $this->tmp_name     = $file['tmp_name'];
        $this->error        = $file['error'];
        $this->size         = $file['size'];

        $this->extension    = self::getExtension($this->type);
        $this->new_filename = uniqid(time()) . '.' . $this->extension; // Nome único
    }

    /**
     * Método responsável por alterar a extensão dos arquivos de imagem para webp e manter a extensão dos arquivos que não são de imagem
     * @param string $type
     * @return string
     */
    public static function getExtension(string $type): string
    {
        $ext = explode('/', $type);

        switch ($ext[1]) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'webp':
                $extension = 'webp';
                break;
            default:
                $extension = $ext[1];
                break;
        }
        return $extension;
    }

    /**
     * Método responsável por mover o arquivo de upload
     * @param string $dir
     * @param bool $compress_image
     * @return bool
     */
    public function upload(string $dir, bool $compress_image = false): bool
    {
        // Se houver erro retorna false
        if ($this->error != 0) {
            return false;
        }

        // Verifica se diretório existe, se não existir cria o diretório
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Caminho completo de destino
        $path = "$dir/{$this->new_filename}";

        // Move o arquivo para a pasta de destino
        return (($compress_image) ? $this->compressImage($path, IMAGE_QUALITY) : move_uploaded_file($this->tmp_name, $path));
    }

    /**
     * Método responsável por excluir um arquivo do servidor
     * @param string $path_name
     * @return bool
     */
    public static function delete(string $path_name): bool
    {
        // Verifica se arquivo existe
        if (!file_exists($path_name)) {
            return false;
        }

        // Retorna true se o arquivo foi excluído ou false se não tiver sido excluído
        return (is_dir($path_name)) ?: unlink($path_name);
    }

    /**
     * Método responsável por criar instâncias de File para múltiplos arquivos
     * @param array $files = $_FILES['campo']
     * @return array
     */
    public static function createMultiUpload(array $files)
    {
        // Array para armezanar todos os arquivos
        $uploads = [];

        foreach ($files['name'] as $key => $value) {
            // Array de arquivo
            $file = [
                'name'      => $files['name'][$key],
                'type'      => $files['type'][$key],
                'tmp_name'  => $files['tmp_name'][$key],
                'error'     => $files['error'][$key],
                'size'      => $files['size'][$key],
            ];

            // Adiciona as instancias de File no array uploads
            $uploads[] = new File($file);
        }

        // Retorna array com os arquivos
        return $uploads;
    }

    /**
     * Método responsável por comprimir a imagem e retornar se o arquivo existe
     * @param string $path
     * @param int $quality
     * @return bool
     */
    private function compressImage(string $path, int $quality): bool
    {
        $info = getimagesize($this->tmp_name);
        switch ($info['mime']) {
            case 'image/jpg':
            case 'image/jpeg':
                $image = imagecreatefromjpeg($this->tmp_name);
                break;
            case 'image/png':
                $image = imagecreatefrompng($this->tmp_name);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($this->tmp_name);
                break;
        }

        return (imagewebp($image, $path, $quality) & file_exists($path));
    }
}
