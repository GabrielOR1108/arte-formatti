<?php

namespace App\Controllers;

use CoffeeCode\Router\Router;

abstract class BaseController
{
    public Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Método responsável por retornar uma mensagem customizada às requisições ajax
     * @param string $icon
     * @param string $title
     * @param string $message
     * @param string $location
     * @return void
     */
    public function returnModal(string $icon, string $title, string $message, string $location = null): void
    {
        $res['icon'] = $icon;
        $res['title'] = $title;
        $res['message'] = $message;
        ($location != null) ? $res['location'] = $location : null;
        echo json_encode($res);
        exit;
    }

    /**
     * Método responsável por fazer a verificação do reCAPTCHA
     */
    public static function verifyRecaptcha($recaptcha_response)
    {
        $data = http_build_query(
            [
                'secret' => RECAPTCHA_SECRET_KEY,
                'response' => $recaptcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ]
        );

        $stream = [
            'http' =>
            [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $data
            ]
        ];

        $context  = stream_context_create($stream);
        $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);

        $obj = json_decode($result);

        return ($obj->success == '' || $obj->success != 1);
    }
}
