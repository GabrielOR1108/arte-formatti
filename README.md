# MW-Admin

 ![Makeweb Logo](views/admin/assets/images/logo-makeweb.png "Makeweb Logo")

## Índice

- [Instalação](#instalação)
  - [Arquivos](#arquivos)
  - [Dependências](#dependências)
  - [Configurações](#configurações)
    - [config.php](#configphp)
    - [database.php](#databasephp)

## Instalação

### Arquivos

Baixe os arquivos do diretório na raiz do projeto que está sendo desenvolvido. A estrutura deve ficar parecida com essa:

```` bash
.
├── app
├── database
├── uploads
├── views
├── .gitignore
├── .htaccess
├── .composer.json
├── index.php
└── README.md
````

### Dependências

Dentro do diretório raiz, execute o comando abaixo para instalar as dependências PHP necessárias

```` bash
composer install
````

Após instalar as dependências do composer, a estrutura do projeto deve se alterar dessa forma:

```` bash
.
├── app
├── database
├── uploads
├── vendor # Nova pasta
├── views
├── .gitignore
├── .htaccess
├── .composer.json
├── index.php
└── README.md
````

após baixar as dependências do composer, entraremos na pasta views/admin/assets para executar o comando abaixo para baixar as dependências do NPM:

````bash
npm install
````

Após instalar as dependências do composer, a estrutura do projeto deve se alterar dessa forma:

```` bash
.
├── app
├── database
├── uploads
├── vendor 
├── views
│   ├── admin         
│   │   ├── assets      
│   │   │   ├── ckeditor
│   │   │   ├── css
│   │   │   ├── datatables
│   │   │   ├── images
│   │   │   ├── js
│   │   │   ├── node_modules # Nova pasta
│   │   │   ├── package-lock.json # Novo arquivo
│   │   │   └── package.json
│   │   └── ...
│   └── ...
└── ...
````

### Configurações

Após estar com as dependências baixadas, devemos realizar algumas configurações no sistema. No arquivo app/config/config.php devemos informar a url base onde o projeto será executado

#### config.php

```` php
...

/**
 * Obtém o protocólo HTTP ou HTTPS
 * @var string
 */
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';

/**
 * Url base da aplicação
 * @var string
 */
define("URL_BASE", "{$protocol}://{$_SERVER['HTTP_HOST']}"); // http://localhost

...
````

#### database.php

Após configurar a url base do nosso projeto, devemos configurar as credenciais de acesso ao banco de dados. Para isso, devemos renomear o arquivo database.example.php dentro de app/config/ para database.php dessa forma:

Antes:

```` bash
.
├── app
│   ├── config         
│   │   ├── config.php      
│   │   ├── database.example.php      
│   │   └── lang.php      
│   └── ...
└── ...
````

Depois:

```` bash
.
├── app
│   ├── config         
│   │   ├── config.php      
│   │   ├── database.php      
│   │   └── lang.php      
│   └── ...
└── ...
````

Agora dentro do arquivo database.php, podemos informar nossas credenciais de acesso ao banco de dados:

database.php:

````php
<?php

/**
 * Host de conexão com o banco de dados
 * @var string
 */
define("DB_HOSTNAME", "");
/**
 * Banco de dados a fazer a conexão
 * @var string
 */
define("DB_DATABASE", "");
/**
 * Usuário de conexão do banco de dados
 * @var string
 */
define("DB_USERNAME", "");
/**
 * Senha do usuário de conexão do banco de dados
 * @var string
 */
define("DB_PASSWORD", "");

````
