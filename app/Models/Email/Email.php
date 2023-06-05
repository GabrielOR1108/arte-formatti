<?php

namespace App\Models\Email;

use App\Connection\Connection;
use App\Models\File\File;
use PDOException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    /**
     * Nome da tabela responsável por armazenar os dados do layout do email
     * @var string
     */
    public string $table = 'mw_email_layout';

    /**
     * Chave identificadora do registro na tabela mw_email_layout
     * @var int|null
     */
    public int|null $id = null;

    /**
     * Nome da imagem do cabeçalho do email
     * @var string|null
     */
    public string|null $top = null;

    /**
     * Nome da imagem do rodapé do email
     * @var string|null
     */
    public string|null $bottom = null;

    /**
     * Método construtor da classe
     */
    public function __construct()
    {
        // Instância de conexão com o banco de dados
        $db = new Connection($this->table);

        // Busca por um registro no banco de dados
        $res = $db->select("`id`, `top`, `bottom`", null, "`id` DESC", "1");

        // Se obter resultados da query associa o nome dos arquivos aos atributos da classe
        if ($res->rowCount() > 0) {
            $arr = $res->fetch(\PDO::FETCH_ASSOC);

            $this->id     = $arr['id'];
            $this->top    = $arr['top'];
            $this->bottom = $arr['bottom'];
        }
    }

    /**
     * Método responsável por realizar o upload das imagens no servidor e 
     * alterar os dados no registro que armazena o nome dos arquivos no banco
     */
    public function uploadTopBottomImages(array $files)
    {
        // Instância de conexão com o banco de dados
        $db = new Connection($this->table);

        // Busca por registros
        $res = $db->select('`top`, `bottom`', null, '`id` DESC', 1);

        // Array para insert
        $values = [];

        // Para cada elemento de $files
        foreach ($files as $key => $value) {
            // Instancia um novo arquivo
            $file = new File($value);

            // Adiciona o novo nome do arquivo no array values
            $values[$key] = $file->new_filename;

            // Diretório de destino
            $path = dirname(__FILE__, 4) . "/uploads/{$this->table}/images";

            // Realiza o upload do arquivo
            $file->upload($path, true);
        }

        try {
            return $db->insert($values); // Last id
        } catch (PDOException $e) {
            return $e; // Retorna PDOException 
        }
    }

    /**
     * Método responsável por validar email
     * @return mixed
     */
    public static function validateEmail(string $email): mixed
    {
        // Sanatiza o email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Valida email
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Método responsável por obter o cabeçalho e o rodapé do email
     * @return array
     */
    private static function getTopBottom(): array
    {
        // Instância de conexão com o banco de dados
        $db = new Connection('mw_email_layout');

        // Busca por um registro no banco de dados
        $res = $db->select("`id`, `top`, `bottom`", null, "`id` DESC", "1");

        // Array com o resultado
        $arr = $res->fetch(\PDO::FETCH_ASSOC);

        // Array para retorno
        $top_bottom = [
            'top'    => $arr['top'],
            'bottom' => $arr['bottom']
        ];

        // Retorna array
        return $top_bottom;
    }

    /**
     * Método responsável por enviar emails
     * @param string $subject,
     * @param string $message
     * @param array $recipients
     * @param string $reply_to
     * @param string $reply_to_name
     * @param string $archive
     * @return bool|Exception
     */
    public static function sendEmail(string $subject, string $message, array $recipients, string $reply_to = null, string $reply_to_name = null, string $archive = null): bool|Exception
    {
        // Instância de PHPMailer; passando `true` habilita as exceptions
        $mail = new PHPMailer(true);

        // Obtém as configurações SMTP do banco
        $smtp_config = \App\Models\Email\Smtp::getSmtpConfig();

        try {
            // Configurações de servidor
            $mail->isSMTP();                                //Send using SMTP
            $mail->SMTPDebug  = SMTP_DEBUG;                 //Enable verbose debug output
            $mail->CharSet    = SMTP_CHARSET;               //Enable verbose debug output
            $mail->SMTPAuth   = SMTP_SMTPAUTH;              //Enable SMTP authentication
            $mail->Host       = $smtp_config['host'];       //Set the SMTP server to send through
            $mail->Username   = $smtp_config['user'];       //SMTP username
            $mail->Password   = $smtp_config['pass'];       //SMTP password
            $mail->SMTPSecure = $smtp_config['auth'];       //Enable implicit TLS encryption
            $mail->Port       = $smtp_config['port'];       //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            //Sender
            $mail->setFrom($smtp_config['user'], $smtp_config['name']);
            // Adiciona destinatários
            foreach ($recipients as $recipient) {
                $mail->addAddress($recipient);
            }

            // Adiciona reply-to
            if ($reply_to != null && $reply_to_name != null) {
                $mail->addReplyTo($reply_to, $reply_to_name);
            }

            //Attachments
            if ($archive != null) {
                $mail->addAttachment($archive);   //Add attachments
            }

            // Obtem o arquivo do cabeçalho e rodapé do email
            $top_bottom = self::getTopBottom();

            // Layout padrão de email
            $body = '
            <!DOCTYPE html>
            <html>   
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <title>CRMake</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                </head>
                <body bgcolor="#eee" style="margin: 0; padding: 50px 0 50px 0; background-color: #eee; padding: 30px 0;">
                    <table bgcolor="#eee"align="center" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #eee">
                        <table align="center" bgcolor="transparent" border="0" bordercolor="#e13a4a" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: transparent; width: 600px; margin: 0 auto;">
                            <tr>     
                                <td align="center" style="padding: 0;">
                                    <img src="' . URL_BASE . '/uploads/mw_email_layout/images/' . $top_bottom['top'] . '" alt="' . $smtp_config['name'] . '" width="600" height="125" style="display: block; margin-bottom: -20px;" />
                                </td>
                            </tr>
                            <tr bgcolor="#fff" style="font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: #989898; background-color: #fff;">
                            ' . $message . '
                            </tr>
                            <tr>
                                <td align="center" bgcolor="transparent" style="padding: 0;">
                                    <img src="' . URL_BASE . '/uploads/mw_email_layout/images/' . $top_bottom['bottom'] . '" alt="' . $smtp_config['name'] . '" width="600" height="50" style="display: block; border-radius: 0 0 15px 15px; margin-top: -20px;" />
                                </td>
                            </tr>
                        </table>
                    </table>
                </body>
            </html>';

            //Content
            $mail->isHTML(true);        //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;

            return $mail->send();
        } catch (Exception $e) {
            return $e;
        }
    }
}
