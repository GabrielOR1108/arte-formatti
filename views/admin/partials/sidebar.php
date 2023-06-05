<div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="true" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header bg-purple">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Menu</h5>
        <button type="button" class="btn btn-hamb btn-close-pers" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="offcanvas-body ps-0 pe-0">
        <div class="d-flex col-12 py-0 px-2 mb-2">
            <div class="col-3">
                <a href="<?= $router->route('admin.user.profile') ?>" class="w-100">
                    <img src="uploads/mw_users/images/<?= $this->e($user_avatar); ?>" class="img-fluid rounded-circle mw-100 my-auto">
                </a>
            </div>
            <div class="col-9 d-flex">
                <div class="my-auto ps-2">
                    <h5><?= $this->e($first_name); ?></h5>
                    <span class="user-category"><?= $this->e($user_level); ?></span>
                </div>
            </div>
        </div>

        <div class="list-group" id="list-group">
            <?php if ($dev_mode & $admin) : ?>
                <a class="list-group-item list-group-item-action border-0 fs-5" data-bs-toggle="collapse" href="#CollapseDev" role="button" aria-expanded="false" aria-controls="CollapseDev"><span style="margin-right: 4px;"><i class="bi bi-terminal-fill"></i></span> Dev</a>
                <div class="collapse" id="CollapseDev" data-bs-parent="#list-group">
                    <div class="list-group" style="border-radius: 0;">
                        <!-- <span class="badge rounded-pill bg-purple ms-auto lh-base">8</span> -->
                        <a href="<?= $router->route('admin.registers.index', ['table' => 'mw_group_menu']) ?>" class="list-group-item list-group-item-action border-top-0 border-start-0 border-end-0 d-flex"><span style="margin-right: 4px;"><i class="bi bi-list-nested"></i></span> Grupo Menu</a>
                        <a href="<?= $router->route('admin.registers.index', ['table' => 'mw_menu']) ?>" class="list-group-item list-group-item-action border-top-0 border-start-0 border-end-0 d-flex"><span style="margin-right: 4px;"><i class="bi bi-table"></i></span> Menu</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($favorites)) :  ?>
                <a class="list-group-item list-group-item-action border-0 fs-5" data-bs-toggle="collapse" href="#CollapseFavorite" role="button" aria-expanded="false" aria-controls="CollapseFavorite"><span style="margin-right: 4px;"><i class="bi bi-heart-fill"></i></span> Favoritos</a>
                <div class="collapse" id="CollapseFavorite" data-bs-parent="#list-group">
                    <div class="list-group" style="border-radius: 0;">
                        <?php foreach ($favorites as $menu) :  ?>
                            <a href="<?= $router->route('admin.registers.index', ['table' => $menu['url']]) ?>" class="list-group-item list-group-item-action border-top-0 border-start-0 border-end-0 d-flex"><span style="margin-right: 4px;"><i class="<?= $this->e($menu['icon']); ?>"></i></span> <?= $this->e($menu['name']); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php foreach ($group_menus as $group_menu) : ?>
                <?php if (!empty($group_menu['menus'])) :  ?>
                    <a class="list-group-item list-group-item-action border-0 fs-5" data-bs-toggle="collapse" href="#Collapse<?= $this->e($group_menu['id']); ?>" role="button" aria-expanded="false" aria-controls="Collapse<?= $this->e($group_menu['id']); ?>"><span style="margin-right: 4px;"><i class="<?= $this->e($group_menu['icon']); ?>"></i></span> <?= $this->e($group_menu['name']); ?></a>
                    <div class="collapse" id="Collapse<?= $this->e($group_menu['id']); ?>" data-bs-parent="#list-group">
                        <div class="list-group" style="border-radius: 0;">
                            <!-- <span class="badge rounded-pill bg-purple ms-auto lh-base">8</span> -->
                            <?php foreach ($group_menu['menus'] as $menu) : ?>
                                <a href="<?= $router->route('admin.registers.index', ['table' => $menu['url']]) ?>" class="list-group-item list-group-item-action border-top-0 border-start-0 border-end-0 d-flex"><span style="margin-right: 4px;"><i class="<?= $this->e($menu['icon']); ?>"></i></span> <?= $this->e($menu['name']); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>

            <a class="list-group-item list-group-item-action border-0 fs-5" data-bs-toggle="collapse" href="#CollapseConfig" role="button" aria-expanded="false" aria-controls="CollapseConfig"><span style="margin-right: 4px;"><i class="bi bi-gear-fill"></i></span> Configurações</a>
            <div class="collapse" id="CollapseConfig" data-bs-parent="#list-group">
                <div class="list-group" style="border-radius: 0;">
                    <?php if ($admin) : ?>
                        <a href="<?= $router->route('admin.settings.index') ?>" class="list-group-item list-group-item-action border-top-0 border-start-0 border-end-0 d-flex"><span style="margin-right: 4px;"><i class="bi bi-gear"></i></span> Configurações</a>
                        <a href="<?= $router->route('admin.user.users') ?>" class="list-group-item list-group-item-action border-top-0 border-start-0 border-end-0 d-flex"><span style="margin-right: 4px;"><i class="bi bi-people"></i></span> Usuários</a>
                    <?php endif; ?>
                    <a href="<?= $router->route('admin.user.profile') ?>" class="list-group-item list-group-item-action border-top-0 border-start-0 border-end-0 d-flex"><span style="margin-right: 4px;"><i class="bi bi-person"></i></span> Perfil</a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-area">
        <small class="text-muted">Versão 0.6.10</small>
    </div>
</div>