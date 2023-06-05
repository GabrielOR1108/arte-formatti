<?php $this->layout('admin/master') ?>

<div class="container">
    <div class="mt-3 mb-2 d-flex">
        <h4><?= "<i class=\"bi bi-$table_icon\"></i> $table_title: Editando registro ($id)"; ?></h4>
        <a class="btn border ms-auto" href="<?= $router->route('admin.registers.index', ['table' => $slug]) ?>"><i class="bi bi-arrow-left"></i> Voltar</a>
    </div>

    <form id="form-update" class="pb-5 form-register" action="<?= $router->route('admin.registers.edit', ['table' => $slug, 'id' => $id]) ?>" method="POST" enctype="multipart/form-data">
        <div class="row mb-3 col-12">
            <?php foreach ($inputs as $input) : ?>
                <?php if ($input['type'] == 'int') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="number" value="<?= $values[$input['name']] ?>" class="form-control" min="0" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'enum') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <?php foreach ($input['options'] as $option) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="<?= $this->e($option['value']) ?>" name="<?= $this->e($input['name']); ?>" <?= ($values[$input['name']] == $option['value']) ? 'checked' : ''  ?> required>
                                <label class="form-check-label"><?= $this->e($option['text']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'foreign_key') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <select name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" class="form-select" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                            <?php if ($input['required'] == '') : ?>
                                <option value="">Não informado</option>
                            <?php endif; ?>

                            <?php foreach ($input['options'] as $option) : ?>
                                <option value="<?= $this->e($option['value']); ?>" <?= ($values[$input['name']] == $option['value']) ? 'selected' : ''  ?>><?= $this->e($option['text']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php if (array_key_exists($input['name'], MASK_FIELDS)) : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="<?= MASK_FIELDS[$input['name']]['input-type'] ?>" class="form-control <?= MASK_FIELDS[$input['name']]['class'] ?>" value="<?= $values[$input['name']] ?>" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php continue;
                endif; ?>

                <?php if ($input['type'] == 'varchar') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="text" value="<?= $values[$input['name']] ?>" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'image') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']) ?> (L: <?= $this->e($input['dimensions']['width']) ?>px, A: <?= $this->e($input['dimensions']['height']) ?>px):</label>
                        <input type="file" accept="image/*" class="form-control" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']) ?>" data-width="<?= $this->e($input['dimensions']['width']) ?>" data-height="<?= $this->e($input['dimensions']['height']) ?>" <?= $this->e($input['readonly']); ?> <?= (isset($values[$input['name']]) && !is_null($values[$input['name']])) ? '' : 'required'; ?>>
                        <div class="col-lg-6 col-md-6 col-sm-12 mt-3" style="display: none;">
                            <label class="text-muted"><small>Preview:</small></label>
                            <img id="<?= $this->e($input['name']); ?>_preview" class="w-100">
                        </div>
                        <?php if (isset($values[$input['name']]) && !is_null($values[$input['name']])) : ?>
                            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                                <label class="text-muted"><small><?= $this->e($input['title']); ?>:</small></label>
                                <img src="uploads/<?= $this->e($table); ?>/images/<?= $values[$input['name']] ?>" class="w-100" alt="">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'file') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="file" accept=".doc, .docx, .pdf" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e($input['readonly']); ?> <?= (isset($values[$input['name']]) && !is_null($values[$input['name']])) ? '' : 'required'; ?>>
                    </div>
                    <?php if (isset($values[$input['name']]) && !is_null($values[$input['name']])) : ?>
                        <div class="col-lg-2 col-md-2 col-sm-12 mb-3">
                            <label class="text-muted"><small><?= $this->e($input['title']); ?>:</small></label><br>
                            <a href="uploads/<?= $this->e($table); ?>/files/<?= $values[$input['name']] ?>" class="btn btn-outline-secondary p-5" style="font-size: 2.5rem;" target="_blank"><i class="bi bi-file-earmark"></i></a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($input['type'] == 'tinytext') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <textarea class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" rows="3" placeholder="<?= $this->e($input['title']); ?>" maxlength="255" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>><?= $values[$input['name']] ?></textarea>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'ckeditor') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <textarea class="form-control" id="<?= $this->e($input['name']); ?>" rows="3" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>></textarea>
                        <script>
                            CKEDITOR.replace('<?= $this->e($input['name']); ?>');
                            CKEDITOR.instances['<?= $input['name']; ?>'].setData(`<?= $values[$input['name']]; ?>`);
                        </script>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'gallery') : ?>
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']) ?>:</label>
                    <div class="gallery-group" id="wrapper_<?= $this->e($input['name']); ?>">
                    </div>
                    <div class="d-flex">
                        <a class="btn fa-lg gradient-custom-2 mb-3 ms-auto" id="add_btn_<?= $this->e($input['name']); ?>"><i class="bi bi-plus-lg"></i> Adicionar imagem</a>
                    </div>

                    <?php if (isset($values[$input['name']]) && !is_null($values[$input['name']])) : ?>

                        <div class="row row-cols-1 row-cols-md-4 gal-area mb-4" data-id="<?= $this->e($input['name']); ?>">

                            <?php foreach (json_decode($values[$input['name']], true) as $key => $image) : ?>
                                <div class="col">
                                    <div class="image-card-title">
                                        <small class="text-muted" id="label_<?= $key ?>"><?= $image['name']; ?></small>
                                    </div>
                                    <div class=" card">
                                        <div class="input-group" style="display: none;">
                                            <input type="text" class="form-control" data-id="<?= $key; ?>" value="<?= $image['name']; ?>">
                                            <button type="button" class="btn btn-primary save-edit-gal"><i class="bi bi-save"></i></button>
                                            <button type="button" class="btn cancel-edit-gal"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                        <button type="button" class="btn border edit-gal"><i class="bi bi-pencil-square"></i> Editar</button>
                                        <img src="uploads/<?= $this->e($table); ?>/images/<?= $image['src'] ?>" class="card-img-top" alt="<?= $image['name']; ?>" data-id="<?= $key; ?>">
                                        <button type="button" class="btn btn-danger del-gal"><i class="bi bi-trash"></i> Excluir</button>
                                    </div>
                                </div>

                            <?php endforeach; ?>

                            <script>
                                $(function() {
                                    var wrapper = $('#wrapper_<?= $this->e($input['name']); ?>');
                                    var fieldHtml = `
                                            <div class="input-group mb-3">
                                                <input type="file" accept="image/*" class="form-control" name="<?= $this->e($input['name']); ?>[]" required data-gallery="true" />
                                                <input type="text" class="form-control" name="<?= $this->e($input['name']); ?>[]" value="" maxlength="40" placeholder="Título" aria-label="Título" required>
                                                <a class="btn btn-danger rm-gallery-field"><i class="bi bi-trash"></i></a>
                                            </div>
                                            `;

                                    $(document).on('click', '#add_btn_<?= $this->e($input['name']); ?>', function() {
                                        $(wrapper).append(fieldHtml);
                                    });

                                    $(document).on('click', '.rm-gallery-field', function() {
                                        var _t = $(this);
                                        _t.parent().remove();
                                    });
                                    $(document).on('click', '.edit-gal', function(e) {
                                        e.preventDefault();
                                        var _t = $(this);
                                        _t.hide();
                                        _t.parent().find('.input-group').fadeIn();
                                    });

                                    $(document).on('click', '.cancel-edit-gal', function(e) {
                                        e.preventDefault();
                                        var _t = $(this);
                                        _t.parent().hide();
                                        _t.closest('.card').find('.edit-gal').fadeIn();
                                    });

                                    $(document).on('click', '.save-edit-gal', function(e) {
                                        e.preventDefault();
                                        var img_index = $(this).parent().find('input[type=text]').data('id');
                                        var img_name = $(this).parent().find('input[type=text]').val();
                                        var field = $(this).closest('.gal-area').data('id');

                                        $.ajax({
                                            url: '<?= $router->route('admin.registers.gallery.updateimagename') ?>',
                                            type: 'PATCH',
                                            dataType: 'json',
                                            data: {
                                                id: '<?= $id; ?>',
                                                table: '<?= $table; ?>',
                                                field: field,
                                                img_index: img_index,
                                                img_name: img_name
                                            },
                                            cache: false,
                                            success: function(res) {
                                                Swal.fire({
                                                    icon: (res.icon) ? res.icon : 'error',
                                                    title: (res.title) ? res.title : 'Oops...',
                                                    text: (res.message) ? res.message : 'Ocorreu um erro.',
                                                });
                                                if (res.icon == 'success') {
                                                    $(`#label_${img_index}`).text(img_name);
                                                }
                                            },
                                            error: function() {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Oops...',
                                                    text: 'Algo deu errado!',
                                                })
                                            }
                                        });
                                    });

                                    $(document).on('click', '.del-gal', function(e) {
                                        e.preventDefault();
                                        var _t = $(this);
                                        var img_index = $(this).parent().find('input[type=text]').data('id');
                                        var img_name = $(this).parent().find('input[type=text]').val();
                                        var field = _t.closest('.gal-area').data('id');
                                        Swal.fire({
                                            title: 'Atenção!',
                                            text: `Tem certeza que deseja excluir a imagem "${img_name}"?`,
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Sim, tenho certeza',
                                            cancelButtonText: 'Cancelar',
                                        }).then((result) => {
                                            _t.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                                            if (result.isConfirmed) {
                                                $.ajax({
                                                    url: "<?= $router->route('admin.registers.gallery.deleteimagename') ?>",
                                                    type: 'DELETE',
                                                    dataType: 'json',
                                                    data: {
                                                        id: '<?= $id; ?>',
                                                        table: '<?= $table; ?>',
                                                        field: field,
                                                        img_index: img_index
                                                    },
                                                    cache: false,
                                                    success: function(res) {
                                                        Swal.fire({
                                                            icon: (res.icon) ? res.icon : 'error',
                                                            title: (res.title) ? res.title : 'Oops...',
                                                            text: (res.message) ? res.message : 'Ocorreu um erro.',
                                                        });
                                                        if (res.icon == 'success') {
                                                            _t.closest('.col').remove();
                                                        }
                                                        if ($('.gal-area').children('div.col').length < 1) {
                                                            $(`input[name='${field}[]'`).attr('required', 'true');
                                                        }
                                                    },
                                                    error: function() {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Oops...',
                                                            text: 'Algo deu errado!',
                                                        })
                                                    }
                                                });
                                            } else {
                                                _t.html('<i class="bi bi-trash"></i> Excluir');
                                            }
                                        });
                                    });
                                });
                            </script>
                        </div>

                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($input['type'] == 'date') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="date" value="<?= $values[$input['name']] ?>" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'datetime') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="datetime-local" value="<?= $values[$input['name']] ?>" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'time') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="time" value="<?= $values[$input['name']] ?>" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>

        <div class="d-flex">
            <button type="submit" class="btn btn-outline-success ms-auto"><i class="bi bi-save"></i> Atualizar</button>
        </div>
    </form>
</div>

<script>
    $(function() {
        var $modal = $("#image_modal");
        var $image = $("#image_demo");
        var cropper, files, img_width, img_height, input, input_id, images_blobs = {};

        $($('input[accept="image/*"]')).on('change', function(e) {
            files = e.target.files;
            input = $(this);
            input_id = input.attr('id');
            if (!input.data('gallery')) {
                img_width = $(this).data('width');
                img_height = $(this).data('height');
                var done = function(url) {
                    $image.attr('src', url);
                    $modal.modal('show');
                }

                if (files && files.length > 0) {
                    reader = new FileReader();
                    reader.onload = function(eve) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(files[0]);
                }
            }
        });

        $modal.on('shown.bs.modal', function() {
            cropper = new Cropper($image[0], {
                aspectRatio: (img_width / img_height),
                viewMode: 1,
                dragMode: 'move',
                guides: true,
                center: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        $("#image_crop").click(function() {
            var _preview = $(`#${input_id}_preview`);

            canvas = cropper.getCroppedCanvas({
                width: img_width,
                height: img_height
            });
            canvas.toBlob(function(blob) {
                let imageUrl = URL.createObjectURL(blob);
                _preview.attr('src', imageUrl);
                images_blobs[input_id] = (blob);
            });
            _preview.parent().show();
            $modal.modal('hide');
        });

        $("#form-update").submit(function(e) {
            e.preventDefault();

            var _t = $(this);
            var form = _t[0];
            var formData = new FormData(form);
            var btn = _t.find('button[type=\"submit\"]').html();
            var btn_w = _t.find('button[type=\"submit\"]').width();

            $.each(images_blobs, function(i, val) {
                formData.delete(i);
                formData.append(i, val)
            })

            $("#form-update").find('input[accept=".doc, .docx, .pdf"]').each(function(i, val) {
                if (this.files.length == 0) {
                    formData.delete(this.name);
                }
            });

            $.each(CKEDITOR.instances, function() {
                formData.append(this.name, this.getData());
            })

            var redirect_url;
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                dataType: 'json',
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    _t.find('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').width(btn_w);
                },
                success: function(res) {
                    redirect_url = res.location;
                    redirect = (res.icon == 'success');
                    Swal.fire({
                        allowOutsideClick: (res.icon != 'success'),
                        icon: (res.icon) ? res.icon : 'error',
                        title: (res.title) ? res.title : 'Oops...',
                        text: (res.message) ? res.message : 'Ocorreu um erro.',
                    }).then((result) => {
                        if (redirect) {
                            if (result.isConfirmed) {
                                location.href = `${redirect_url}`;
                            }
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Algo deu errado!',
                    })
                }
            }).then(function() {
                _t.find('button[type="submit"]').html('<i class="bi bi-pencil-square"></i> Atualizar');
            });
        });
    });
</script>