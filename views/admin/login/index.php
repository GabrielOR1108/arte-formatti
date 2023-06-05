<!DOCTYPE HTML>
<html lang="pt-br" data-bs-theme="light">

<head>
    <?= $this->insert('admin/partials/header') ?>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar">
            <div class="container-fluid">
                <div class="ms-auto btn-group">
                    <button class="btn ps-1 py-1 me-2" type="button" id="bd-theme" data-bs-toggle="dropdown" aria-expanded="false">
                        <i id="theme-icon-active" class="bi bi-moon-stars-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-navbar" aria-labelledby="bd-theme-text">
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                <i class="bi bi-brightness-high-fill"></i><span class="me-2"></span> Claro
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="dark" aria-pressed="true">
                                <i class="bi bi-moon-stars-fill"></i><span class="me-2"></span> Escuro
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                                <i class="bi bi-circle-half"></i><span class="me-2"></span> Automático
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container py-5">
            <div class="row d-flex justify-content-center align-items-center pt-5 mt-5">
                <div class="col-xl-8">
                    <div class="card border-0 shadow-lg rounded-3">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="card-body py-5 mx-md-4">

                                    <div class="text-center mt-1 mb-5 pb-1">
                                        <img src="views/admin/assets/images/logo-makeweb.png" style="max-width: 200px;" alt="MakeWeb">
                                    </div>

                                    <form id="form-login" action="<?= $router->route('admin.login.login') ?>">
                                        <div class="form-floating mb-4">
                                            <input type="email" id="email" name="email" class="form-control" placeholder="email@email.com" required />
                                            <label for="email"><i class="bi bi-envelope"></i> Email</label>
                                        </div>

                                        <div class="form-floating mb-4">
                                            <input type="password" id="password" name="password" class="form-control inp-pass" placeholder="password" required />
                                            <label for="password"><i class="bi bi-lock"></i> Senha</label>
                                            <i id="eye" class="bi bi-eye"></i>
                                        </div>

                                        <div class="d-grid pt-2">
                                            <button class="btn btn-primary btn-block fa-lg mb-3" id="btn-login" type="submit">Login</button>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-center">
                                            <a class="text-muted text-decoration-none" href="#" data-bs-toggle="modal" data-bs-target="#forgotPassModal">Esqueceu a senha?</a>
                                        </div>

                                    </form>

                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                                <div class="text-white py-4 px-4 mx-md-2">
                                    <h1 class="mb-4"><?= $this->e($title); ?></h1>
                                    <p class="lead mb-0"><?= $this->e($description); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="forgotPassModal" tabindex="-1" aria-labelledby="forgotPassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPassModalLabel"><i class="bi bi-lock"></i> Recuperação de senha
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Informe o e-mail de cadastro da conta para receber as instruções para
                        redifinir a sua senha de acesso ao painel de gerenciamento MakeWeb.</p>
                    <form id="form-pass" class="form-submit" action="<?= $router->route('admin.login.forgotpass') ?>" method="POST" class="mt-4 mb-4">
                        <div class="form-floating">
                            <input type="email" id="email" name="email" class="form-control" placeholder="email" required />
                            <label for="email"><i class="bi bi-envelope"></i> Email</label>
                        </div>
                        <div class="input-group my-4">
                            <button type="submit" id="btn-change-pass" class="btn btn-primary btn-block mx-auto"><i class="bi bi-unlock-fill"></i> Recuperar senha</button>
                        </div>
                    </form>
                    <p class="text-muted mb-2">Não está conseguindo recuperar sua senha?</br><a href="https://www.makeweb.com.br/contato" style="text-decoration: none;" target="blank">Entre em contato <i class="bi bi-chat"></i></a></p>
                </div>

            </div>
        </div>
    </div>
</body>

</html>