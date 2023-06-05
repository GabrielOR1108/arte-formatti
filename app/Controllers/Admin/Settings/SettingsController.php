<?php

namespace App\Controllers\Admin\Settings;

use App\Controllers\Admin\Controller;
use App\Models\Cookies\Cookies;
use App\Models\Email\Email;
use App\Models\Email\Smtp;
use App\Models\Metatags\Metatags;
use App\Models\Recipients\Recipients;
use App\Models\Users\User;

class SettingsController extends Controller
{
    /**
     * Método responsável por carregar a view das configurações SMTP
     */
    public function index()
    {
        // Verifica se usuário é admin, caso não seja, retorna 404
        if (!User::isAdmin($_SESSION['user']['level'])) {
            $this->return404();
            exit;
        }

        // Obtém as informações dos cookies
        $objCookies = new Cookies();

        // Obtém as informações dos destinarários
        $objRecipients = new Recipients();

        // Obtém as informações do layout
        $objEmail = new Email();

        // Obtem as metatags
        $objMetatags = new Metatags();

        // Obtem as configurações SMTP
        $objSmtp = new Smtp();

        echo $this->view('admin/settings/settings', [
            'cookies' => [
                'title' => $objCookies->title,
                'text' => $objCookies->text,
            ],

            'recipients' => [
                'columns'   => $objRecipients->getData(),
                'table'     => MW_RECIPIENTS_TABLE
            ],

            'email' => [
                'images' => [
                    'top'    => $objEmail->top,
                    'bottom' => $objEmail->bottom
                ],
                'dimensions' => [
                    'top' => [
                        'width'  => IMAGE_FIELDS['mw_email_layout']['top']['width'],
                        'height' => IMAGE_FIELDS['mw_email_layout']['top']['height'],
                    ],
                    'bottom' => [
                        'width'  => IMAGE_FIELDS['mw_email_layout']['bottom']['width'],
                        'height' => IMAGE_FIELDS['mw_email_layout']['bottom']['height'],
                    ]
                ],
            ],

            'metatags' => [
                'description' => $objMetatags->description,
                'keywords' => $objMetatags->keywords
            ],

            'smtp' => [
                'host' => $objSmtp->host,
                'user' => $objSmtp->user,
                'pass' => $objSmtp->pass,
                'name' => $objSmtp->name,
                'auth' => $objSmtp->auth,
                'port' => $objSmtp->port,
            ],
            'router' => $this->router
        ]);
    }
}
