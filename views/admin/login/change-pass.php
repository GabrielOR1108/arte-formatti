<!DOCTYPE HTML>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MakeWeb | Admin</title>
    <base href="<?= $this->e($base_href) ?>">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="views/admin/assets/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="views/admin/assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="views/admin/assets/node_modules/@popperjs/core/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="views/admin/assets/node_modules/bootstrap-icons/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="views/admin/assets/node_modules/jquery/dist/jquery.min.js"></script>

    <!-- Sweetalert -->
    <script src="views/admin/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <!-- Handwrite Js and CSS -->
    <link rel="stylesheet" href="views/admin/assets/css/style.css">
    <script src="views/admin/assets/js/main.js"></script>
    <script src="views/admin/assets/js/colormodetoggler.js"></script>
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
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-xl-4">
                    <div class="card border-0 shadow-lg rounded-3">
                        <div class="row g-0">
                            <div class="card-body py-5 mx-md-4">
                                <div class="text-center mt-1 mb-5 pb-1">
                                    <img src="views/admin/assets/images/logo-makeweb.png" style="max-width: 200px;" alt="MakeWeb">
                                </div>
                                <form id="form-pass" action="<?= $router->route('admin.login.changepass') ?>" method="POST">
                                    <p class="mb-4">Digite sua nova senha: </p>
                                    <div class="form-outline form-floating mb-4">
                                        <input type="hidden" value="<?= $this->e($hash) ?>" name="hash">
                                        <input type="password" id="password" name="password" class="form-control" placeholder=" " required />
                                        <label for="password"><i class="bi bi-lock"></i> Senha</label>
                                        <i id="eye" class="bi bi-eye eye-pass"></i>
                                    </div>

                                    <div class="form-outline form-floating mb-4">
                                        <input type="password" id="password-repeat" name="password-repeat" class="form-control inp-pass" placeholder=" " required />
                                        <label for="password-repeat"><i class="bi bi-lock"></i> Repetir
                                            senha</label>
                                        <i id="eye-repeat" class="bi bi-eye eye-repeat-pass"></i>
                                    </div>

                                    <div class="d-grid pt-2">
                                        <button class="btn btn-primary btn-block fa-lg mb-3" id="btn-change-pass" type="submit">Atualizar senha</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>



<script type="text/javascript">
    $(document).ready(function() {
        $('#form-pass').submit(function(e) {
            $('#btn-change-pass').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            e.preventDefault();

            var redirect_url;
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.icon == 'success') {
                        redirect_url = res.location;
                        $('#btn-change-pass').text('Atualizar senha');
                        Swal.fire({
                            icon: 'success',
                            title: (res.title) ? res.title : 'Sucesso',
                            text: (res.message) ? res.message : 'Sucesso!',
                            timer: 2000,
                            timerProgressBar: true,
                            showLoaderOnConfirm: true,
                            closeOnConfirm: true,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                                const b = Swal.getHtmlContainer().querySelector('b')
                                timerInterval = setInterval(() => {
                                    b.textContent = Swal.getTimerLeft()
                                }, 100)
                            },
                            willClose: () => {
                                clearInterval(timerInterval)
                            }
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                location.href = redirect_url;
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: (res.title) ? res.title : 'Oops...',
                            text: (res.message) ? res.message : 'Ocorreu um erro.'
                        })
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Algo deu errado!',
                    })
                }
            }).then(function() {
                $('#btn-change-pass').text('Atualizar senha');
            });
        });

        $('#eye').click(function(e) {
            e.preventDefault();

            var icon = $(this);
            var input = $('#password');

            if (icon.hasClass('bi-eye-slash')) {
                icon.removeClass('bi-eye-slash');
                icon.addClass('bi-eye');
                input.attr('type', 'password');
            } else {
                icon.removeClass('bi-eye');
                icon.addClass('bi-eye-slash');
                input.attr('type', 'eye');
            }
        });

        $('#eye-repeat').click(function(e) {
            e.preventDefault();

            var icon = $(this);
            var input = $('#password-repeat');

            if (icon.hasClass('bi-eye-slash')) {
                icon.removeClass('bi-eye-slash');
                icon.addClass('bi-eye');
                input.attr('type', 'password');
            } else {
                icon.removeClass('bi-eye');
                icon.addClass('bi-eye-slash');
                input.attr('type', 'eye');
            }
        });
    });
</script>

</html>