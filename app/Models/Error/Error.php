<?php

namespace App\Models\Error;

class Error
{
    /**
     * Método responsável pór retornar uma mensagem de erro de acordo com o cód.
     * @param int|string $code
     * @return string
     */
    public static function getErrorMessage(int | string $code): string
    {
        switch ($code) {
            case "403":
                $errmsg = 'Ops, parece que você não tem autorização para ver esta página.';
                break;
            case "404":
                $errmsg = 'Ops, parece que a página que procura não existe.';
                break;
            case "500":
                $errmsg = 'Ops, o servidor está passando por alguns problemas. Voltaremos logo.';
                break;
            default:
                $errmsg = 'Ops, houve um erro inesperado.';
                break;
        }

        return $errmsg;
    }
}
