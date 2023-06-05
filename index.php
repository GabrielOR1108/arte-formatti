<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/config/config.php';
require __DIR__ . '/app/config/database.php';
require __DIR__ . '/app/config/lang.php';

use CoffeeCode\Router\Router;
use App\Models\Users\Login;
use League\Plates\Engine; // ESSA LINHA DEVE SER REMOVIDA ASSIM QUE FOREM CRIADAS AS CONTROLLERS

$router = new Router(URL_BASE);

/* REMOVER APÓS ADICIONAR AS CONTROLLERS */
function loadView($view, $data = [])
{
    $viewsPath = dirname(__FILE__) . '/views';
    $template = new Engine($viewsPath);
    $template->addData([
        'url_base' => URL_BASE . '/',
    ], 'site/partials/head');
    return $template->render($view, $data);
}
/******************************************/


/**
 * Novas rotas aqui
 */
$router->get('/', function () {
    echo loadView('site/home/index');
    exit;
});

$router->namespace("App\Controllers\Site");

// Rota de erro
$router->get("/ooops/{errcode}", "Error\ErrorController:home", 'site.error.index');

// Login
$router->group('mw-admin', middleware: \App\Middlewares\Admin\LoginMiddleware::class)->namespace("App\Controllers\Admin\Users");
$router->get("/login", "LoginController:index", "admin.login.index"); // View Login
$router->get("/change-pass/{hash}", "LoginController:changePassView", 'admin.changepass.index'); // View Change Pass
$router->post("/login", "LoginController:login", 'admin.login.login');
$router->post("/login/forgot-pass", "LoginController:forgotPass", 'admin.login.forgotpass');
$router->post("/login/change-pass", "LoginController:changePass", 'admin.login.changepass');

// Rotas com login
$router->group('mw-admin', middleware: \App\Middlewares\Admin\AuthMiddleware::class)->namespace("App\Controllers\Admin");

// GET
$router->get("/", "Home\HomeController:index", "admin.home.index");
$router->get("/home", "Home\HomeController:index", "admin.home.home");

// Tabelas e registros
// GET
$router->get("/registers/{table}", "Registers\RegistersController:table", 'admin.registers.index');
$router->get("/registers/{table}/new", "Registers\RegistersController:new", 'admin.registers.new');
$router->get("/registers/{table}/see/{id}", "Registers\RegistersController:see", 'admin.registers.see');
$router->get("/registers/{table}/edit/{id}", "Registers\RegistersController:edit", 'admin.registers.edit');
$router->get("/registers/get-registers", "Registers\RegistersController:getRegisters", 'admin.registers.getregisters');

// POST
$router->post("/registers/{table}/new", "Registers\RegistersController:insert");
$router->post("/registers/{table}/edit/{id}", "Registers\RegistersController:update");

// PATCH
$router->patch("/registers/activate", "Registers\RegistersController:activate", 'admin.registers.activate');
$router->patch("/registers/favorite", "Registers\RegistersController:favorite", 'admin.registers.favorite');
$router->patch("/registers/gallery/update-image-name", "Registers\RegistersController:updateGalleryImageName", 'admin.registers.gallery.updateimagename');

// DELETE
$router->delete("/registers/delete", "Registers\RegistersController:delete", 'admin.registers.delete');
$router->delete("/registers/gallery/delete-image", "Registers\RegistersController:deleteGalleryImage", 'admin.registers.gallery.deleteimagename');

// Logout
// GET
$router->get("/logout", "Users\LoginController:logout", 'admin.logout');

// Usuários
// GET
$router->get("/user/users", "Users\UserController:users", 'admin.user.users');
$router->get("/user/new", "Users\UserController:new", 'admin.user.new');
$router->get("/user/profile", "Users\UserController:profile", 'admin.user.profile');
$router->get("/user/get-users", "Users\UserController:getUsers", 'admin.user.getusers');

// POST
$router->post("/user/create-user", "Users\UserController:createUser", 'admin.user.create');
$router->post("/user/update-profile", "Users\UserController:updateProfile", 'admin.user.update');
$router->post("/user/update-password", "Users\UserController:updatePassword", 'admin.user.updatepass');
$router->post("/user/update-level", "Users\UserController:updateLevel", 'admin.user.updatelevel');
$router->post("/user/delete", "Users\UserController:deleteUser", 'admin.user.delete');

// PATCH
$router->post("/user/update-avatar", "Users\UserController:updateAvatar", 'admin.user.updateavatar');

// Configurações
// GET
$router->get("/settings", "Settings\SettingsController:index", 'admin.settings.index');

// Destinatários
$router->get("/settings/get-recipient", "Recipients\RecipientsController:getRecipient", 'admin.recipients.getrecipient');
$router->get("/settings/get-recipients", "Recipients\RecipientsController:getRecipients", 'admin.recipients.getrecipients');
$router->post("/settings/add-recipient", "Recipients\RecipientsController:addRecipient", 'admin.recipients.new');
$router->put("/settings/edit-recipient", "Recipients\RecipientsController:editRecipient", 'admin.recipients.edit');

// Cookies
$router->post("/settings/update-cookies", "Cookies\CookiesController:updateInfo", 'admin.settings.updatecookies');

// Email
$router->post("/settings/update-email-layout", "Email\EmailController:updateEmailLayout", 'admin.settings.updateemaillayout');
$router->post("/settings/update-smtp-config", "Email\SmtpController:updateSmtpConfig", 'admin.settings.updatesmtpconfig');

// Metatags
$router->post("/settings/update-metatags", "Metatags\MetatagsController:updateMetatags", 'admin.settings.updatemetatags');

$router->get("/ooops/{errcode}", "Error\ErrorController:index", "admin.error.index");

$router->dispatch();

if ($router->error()) {
    if ((new Login)->isLogged()) {
        $router->redirect('admin.error.index', ['errcode' => $router->error()]);
    }

    if (!(new Login)->isLogged()) {
        $router->redirect('site.error.index', ['errcode' => $router->error()]);
    }
}
