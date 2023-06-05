<nav class="navbar border-bottom shadow-sm">
    <div class="container-fluid">
        <button class="btn btn-hamb" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand mb-0 me-auto h1" href="<?= $router->route('admin.home.home') ?>" style="height: 100%; width: 10rem;"><img src="views/admin/assets/images/logo-makeweb.png" class="mh-100 h-100 img-fluid" alt=""></a>
        <div class="btn-group">
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

            <button class="btn border dropdown-toggle rounded ps-1 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="uploads/mw_users/images/<?= $this->e($user_avatar); ?>" style="max-width: 30px;" class="img-fluid h-base rounded-circle">
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-navbar">
                <li>
                    <p class="dropdown-item mb-0">Logado como <?= $this->e($first_name); ?></p>
                </li>
                <li><a class="dropdown-item" href="<?= $router->route('admin.user.profile') ?>"><i class="bi bi-person-circle"></i> Meu perfil</a></li>
                <li><a class="dropdown-item" href="<?= $router->route('admin.logout'); ?>"><i class="bi bi-box-arrow-left"></i> Sair</a></li>
            </ul>
        </div>
    </div>
</nav>