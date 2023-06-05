<?php $this->layout('admin/master') ?>
<div class="content-home">
    <h1>Olá, <?= $this->e($first_name) ?>!</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4 cards-home">
        <?php if (!empty($favorites)) : ?>
            <div class="col">
                <h5 style="padding-left: 6px;"><i class="bi bi-heart-fill"></i></span> Favoritos</h5>
                <div class="card">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($favorites as $menu) : ?>
                            <li class="list-group">
                                <a href="<?= $router->route('admin.registers.index', ['table' => $menu['url']]) ?>" class="list-group-item list-group-item-action border-0"><span style="margin-right: 4px;"><i class="<?= $this->e($menu['icon']) ?>"></i></span> <?= $this->e($menu['name']) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        <?php foreach ($group_menus as $group_menu) : ?>
            <?php if (!empty($group_menu['menus'])) :  ?>
                <div class="col">
                    <h5 style="padding-left: 6px;"><i class="<?= $this->e($group_menu['icon']); ?>"></i></span> <?= $this->e($group_menu['name']); ?></h5>
                    <div class="card">
                        <ul class="list-group list-group-flush" style="overflow: overlay;">
                            <?php foreach ($group_menu['menus'] as $menu) : ?>
                                <li class="list-group">
                                    <a href="<?= $router->route('admin.registers.index', ['table' => $menu['url']]) ?>" class="list-group-item list-group-item-action border-0"><span style="margin-right: 4px;"><i class="<?= $this->e($menu['icon']) ?>"></i></span> <?= $this->e($menu['name']) ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>