<?php $this->layout('admin/master') ?>

<div class="container">
    <div class="mt-3 mb-2 d-flex">
        <h4><?= "<i class=\"bi bi-$table_icon\"></i> $table_title: Registro ($id)"; ?></h4>
        <a class="btn border ms-auto" href="<?= $router->route('admin.registers.index', ['table' => $slug]) ?>"><i class="bi bi-arrow-left"></i> Voltar</a>
        <a class="btn btn-purple ms-2" href="<?= $router->route('admin.registers.edit', ['table' => $slug, 'id' => $id]) ?>"><i class="bi bi-pencil-square"></i></a>
    </div>

    <div class="row mb-3 col-12">
        <?php foreach ($inputs as $input) : ?>
            <?php if ($input['type'] == 'int') : ?>
                <div class="mb-3">
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                    <input type="number" value="<?= $values[$input['name']] ?>" class="form-control" min="0" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" readonly>
                </div>
            <?php endif; ?>

            <?php if ($input['type'] == 'enum') : ?>
                <div class="mb-3">
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                    <?php foreach ($input['options'] as $option) : ?>
                        <?php if ($option['value'] == $values[$input['name']]) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="<?= $this->e($option['value']) ?>" name="<?= $this->e($input['name']); ?>" checked>
                                <label class="form-check-label"><?= $this->e($option['text']) ?></label>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($input['type'] == 'foreign_key') : ?>
                <div class="mb-3">
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                    <select name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" class="form-select" readonly disabled>
                        <?php if ($input['required'] == '') : ?>
                            <option value="">Não informado</option>
                        <?php endif; ?>

                        <?php foreach ($input['options'] as $option) : ?>
                            <option <?= ($option['value'] == $values[$input['name']]) ? 'selected' : ''; ?>><?= $this->e($option['text']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if ($input['type'] == 'varchar') : ?>
                <div class="mb-3">
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                    <input type="text" value="<?= $values[$input['name']] ?>" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" readonly>
                </div>
            <?php endif; ?>

            <?php if ($input['type'] == 'image') : ?>
                <div class="mb-3">
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']) ?> (L: <?= $this->e($input['dimensions']['width']) ?>px, A: <?= $this->e($input['dimensions']['height']) ?>px):</label>
                    <?php if (isset($values[$input['name']]) && !is_null($values[$input['name']])) : ?>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <img src="uploads/<?= $this->e($table); ?>/images/<?= $values[$input['name']] ?>" class="w-100" alt="">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($input['type'] == 'file') : ?>
                <?php if (isset($values[$input['name']]) && !is_null($values[$input['name']])) : ?>
                    <div class="col-lg-2 col-md-2 col-sm-12 mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <a href="uploads/<?= $this->e($table); ?>/files/<?= $values[$input['name']] ?>" class="btn btn-outline-secondary p-5" style="font-size: 2.5rem;" target="_blank"><i class="bi bi-file-earmark"></i></a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($input['type'] == 'tinytext' || $input['type'] == 'ckeditor') : ?>
                <div class="mb-3">
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                    <textarea class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" rows="3" placeholder="<?= $this->e($input['title']); ?>" maxlength="255" readonly><?= strip_tags($values[$input['name']]); ?></textarea>
                </div>
            <?php endif; ?>

            <?php if ($input['type'] == 'gallery') : ?>
                <div class="row row-cols-1 row-cols-md-4 gal-area mb-4" data-id="<?= $this->e($input['name']); ?>">

                    <?php foreach (json_decode($values[$input['name']], true) as $key => $image) : ?>
                        <div class="col">
                            <div class="image-card-title">
                                <small class="text-muted" id="label_<?= $key ?>"><?= $image['name']; ?></small>
                            </div>
                            <div class=" card">
                                <img src="uploads/<?= $this->e($table); ?>/images/<?= $image['src'] ?>" class="card-img-top" alt="<?= $image['name']; ?>" data-id="<?= $key; ?>">
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

            <?php if ($input['type'] == 'date') : ?>
                <div class="mb-3">
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                    <input type="date" value="<?= $values[$input['name']] ?>" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" readonly>
                </div>
            <?php endif; ?>

            <?php if ($input['type'] == 'datetime') : ?>
                <div class="mb-3">
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                    <input type="datetime-local" value="<?= $values[$input['name']] ?>" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" readonly>
                </div>
            <?php endif; ?>

            <?php if ($input['type'] == 'time') : ?>
                <div class="mb-3">
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                    <input type="time" value="<?= $values[$input['name']] ?>" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" readonly>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>
</div>