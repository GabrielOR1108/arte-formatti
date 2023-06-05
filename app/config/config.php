<?php
// Define o timezone do Brasil
date_default_timezone_set('America/Sao_Paulo');

/**
 * Obtém o protocólo HTTP ou HTTPS
 * @var string
 */
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';

/**
 * Url base da aplicação
 * @var string
 */
define("URL_BASE", "{$protocol}://{$_SERVER['HTTP_HOST']}/2023/arte_formatti");

/**
 * Chave secreta do reCAPTCHA
 * @var string
 */
define("RECAPTCHA_SECRET_KEY", '');

/**
 * Variável que define o modo de desenvolvimento
 * @var bool
 */
define("DEV_MODE", true);

/**
 * Tabelas permitidas apenas quando DEV_MODE == true
 * @var array
 */
define("DEV_TABLES", [
    "mw_group_menu",
    "mw_menu"
]);

/**
 * Modo de debug do PHPMailer
 * @var int
 */
define("SMTP_DEBUG", 0);

/**
 * Charset do PHPMailer
 * @var string
 */
define("SMTP_CHARSET", "UTF-8");

/**
 * Ativar autenticação SMTP
 * @var bool
 */
define("SMTP_SMTPAUTH", true);

/**
 * Colunas que não devem aparecer nas dataTables
 * @var array
 */
define("NOT_PERM_COLS", [
    "created_at",
    "updated_at",
    "password",
    "url",
    "password_sender",
    "favorites",
    "gallery",
]);

/**
 * Colunas que aparecem apenas na tela de visualizar um registro
 * @var array
 */
define("ONLY_SEE_FIELDS", [
    "created_at",
    "updated_at",
    "last_login",
]);

/**
 * Colunas que não devem aparecer na tela de cadastrar, editar ou visualizar em nenhuma circustância
 * @var array
 */
define("NOT_PERM_FIELDS", [
    "id",
    "password",
]);


/**
 * Colunas da tabela que não podem ser usadas na pesquisa
 * @var array
 */
define("NOT_SEARCHABLE_COLUMNS", [
    "created_at",
    "updated_at",
    "password",
    "password_sender",
    "favorites",
    "url",
    "image",
    "icon",
]);

/**
 * Chave identificadora do nível de usuário Administrador na tabela mw_user_level
 * @var int
 */
define("ADMIN_LEVEL", 1);

/**
 * Tabelas disponíveis apenas para os admins
 * @var array
 */
define("COMMON_USER_TABLES", [
    // "books",
]);

/**
 * Tabela responsável por armazenar os usuários do sistema
 * @var string
 */
define("MW_USERS_TABLE", "mw_users");

/**
 * Tabela responsável por armazenar os destinatários dos formulários
 * @var string
 */
define("MW_RECIPIENTS_TABLE", "mw_recipients");

/**
 * Tabelas que são apenas para leitura
 * @var array
 */
define("READONLY_TABLES", [
    // "contato",
]);

/**
 * Campos de tabelas que são apenas para leitura
 * @var array
 */
define("READONLY_FIELDS", [
    // "mw_menu" => [
    //     "url"
    // ],
]);

/**
 * Tabelas que podem ser exportadas, como contato, newsletter etc.
 * @var array
 */
define("EXPORT_TABLES", [
    "contato",
    "banner",
]);

/**
 * Campos que devem ser mascarados, como cep, telefone, cpf etc.
 * @var array
 */
define("MASK_FIELDS", [
    "cep" => [
        "class" => "cep-field",
        "input-type" => "text"
    ],

    "cidade" => [
        "class" => "fill-cidade",
        "input-type" => "text"
    ],

    "uf" => [
        "class" => "fill-uf",
        "input-type" => "text"
    ],

    "rua" => [
        "class" => "fill-rua",
        "input-type" => "text"
    ],

    "bairro" => [
        "class" => "fill-bairro",
        "input-type" => "text"
    ],

    "whatsapp" => [
        "class" => "cel-field",
        "input-type" => "text"
    ],

    "telefone" => [
        "class" => "cel-field",
        "input-type" => "text"
    ],

    "celular" => [
        "class" => "cel-field",
        "input-type" => "text"
    ],

    "tel" => [
        "class" => "tel-0800",
        "input-type" => "text"
    ],

    "email" => [
        "class" => "",
        "input-type" => "email"
    ],
]);

/**
 * Array associativo contendo a tabela de origem da foreign key,
 * a coluna, a tabela referenciada, a coluna referenciada e um campo
 * para aparecer no local da foreign key
 * @var array
 */
define("FOREIGN_KEYS", [
    "mw_menu" => [
        "mw_group_menu" => [
            "referenced_tbl"    => "mw_group_menu",
            "referenced_col"    => "id",
            "field_text"        => "name",
        ]
    ],

    "mw_users" => [
        "level" => [
            "referenced_tbl"    => "mw_user_level",
            "referenced_col"    => "id",
            "field_text"        => "level",
        ]
    ],
]);

/**
 * Qualidade da compressão da imagem
 * @var int 0 - 100
 */
define("IMAGE_QUALITY", 85);

/**
 * Arrays associativos contendo a tabela, seus campos de imagem e suas medidas de largura e altura
 * @var array 
 * "table" => [
 *       "field" => [
 *           "width"  => x,
 *           "height" => y
 *       ],
 *  ]
 */
define("IMAGE_FIELDS", [
    "mw_users" => [
        "image" => [
            "width"  => 500,
            "height" => 500
        ],
    ],

    "mw_email_layout" => [
        "top" => [
            "width"  => 600,
            "height" => 125
        ],

        "bottom" => [
            "width"  => 600,
            "height" => 50
        ],
    ],
]);

/**
 * Arrayss associativos contendo a tabela e o campo que é uma galeria
 * 
 * @var array
 */
define("GALLERY_FIELDS", [
    "banners_teste" => [
        "galeria"
    ],
]);

/**
 * Arrays associativos contendo a tabela e o campo que é um arquivo
 * 
 * @var array
 */
define("FILE_FIELDS", [
    // "books" => [
    //     "file"
    // ]
]);
