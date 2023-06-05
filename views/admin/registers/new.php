<?php $this->layout('admin/master') ?>

<div class="container">
    <div class="mt-3 mb-2 d-flex">
        <h4><?= "<i class=\"bi bi-$table_icon\"></i> $table_title: Novo"; ?></h4>
        <a class="btn border ms-auto" href="<?= $router->route('admin.registers.index', ['table' => $slug]) ?>"><i class="bi bi-arrow-left"></i> Voltar</a>
    </div>

    <form id="form-insert" class="pb-5 form-register" action="<?= $router->route('admin.registers.new', ['table' => $slug]) ?>" method="POST" enctype="multipart/form-data">
        <div class="row mb-3 col-12">
            <?php foreach ($inputs as $input) : ?>
                <?php if ($input['type'] == 'int') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="number" class="form-control" min="0" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'checkbox') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="1" role="switch" id="<?= $this->e($input['name']); ?>" name="<?= $this->e($input['name']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'enum') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <?php foreach ($input['options'] as $option) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="<?= $this->e($option['value']) ?>" name="<?= $this->e($input['name']); ?>" required>
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
                                <option value="<?= $this->e($option['value']); ?>"><?= $this->e($option['text']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                <?php endif; ?>

                <?php if (array_key_exists($input['name'], MASK_FIELDS)) : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="<?= MASK_FIELDS[$input['name']]['input-type'] ?>" class="form-control <?= MASK_FIELDS[$input['name']]['class'] ?>" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php continue;
                endif; ?>

                <?php if ($input['type'] == 'varchar') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="text" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'image') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']) ?> (L: <?= $this->e($input['dimensions']['width']) ?>px, A: <?= $this->e($input['dimensions']['height']) ?>px):</label>
                        <input type="file" accept="image/*" class="form-control" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']) ?>" data-width="<?= $this->e($input['dimensions']['width']) ?>" data-height="<?= $this->e($input['dimensions']['height']) ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                        <div class="col-6" style="display: none;">
                            <img id="<?= $this->e($input['name']); ?>_preview" class="w-100 mt-3">
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'file') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="file" accept=".doc, .docx, .pdf" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'tinytext') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <textarea class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" rows="3" placeholder="<?= $this->e($input['title']); ?>" maxlength="255" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>></textarea>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'ckeditor') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <textarea class="form-control" id="<?= $this->e($input['name']); ?>" rows="3" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>></textarea>
                        <script>
                            CKEDITOR.replace('<?= $this->e($input['name']); ?>');
                        </script>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'gallery') : ?>
                    <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']) ?>:</label>
                    <div class="gallery-group" id="wrapper_<?= $this->e($input['name']); ?>">
                        <div class="input-group mb-3">
                            <input type="file" accept="image/*" class="form-control" name="<?= $this->e($input['name']); ?>[]" id="<?= $this->e($input['name']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?> data-gallery="true">
                            <input type="text" class="form-control" name="<?= $this->e($input['name']); ?>[]" value="" maxlength="40" placeholder="Título" aria-label="Título" required>
                        </div>
                    </div>
                    <div class="d-flex">
                        <a class="btn fa-lg gradient-custom-2 mb-3 ms-auto" id="add_btn_<?= $this->e($input['name']); ?>"><i class="bi bi-plus-lg"></i> Adicionar imagem</a>
                    </div>

                    <script>
                        $(function() {
                            var wrapper = $('#wrapper_<?= $this->e($input['name']); ?>');
                            var fieldHtml = `
                                            <div class="input-group mb-4">
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
                        });
                    </script>
                <?php endif; ?>

                <?php if ($input['type'] == 'date') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="date" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'datetime') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="datetime-local" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

                <?php if ($input['type'] == 'time') : ?>
                    <div class="mb-3">
                        <label for="<?= $this->e($input['name']); ?>" class="form-label"><?= $this->e($input['title']); ?>:</label>
                        <input type="time" class="form-control" name="<?= $this->e($input['name']); ?>" id="<?= $this->e($input['name']); ?>" placeholder="<?= $this->e($input['title']); ?>" <?= $this->e("{$input['required']} {$input['readonly']}"); ?>>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>

        <div class="d-flex">
            <button type="submit" class="btn btn-outline-success ms-auto"><i class="bi bi-save"></i> Adicionar</button>
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

        $("#image_cancel").click(function(e) {
            clearImageInputAndPreview();
        });
        $("#image_close_modal").click(function(e) {
            clearImageInputAndPreview();
        });

        function clearImageInputAndPreview() {
            $(`#${input_id}`).val('');
            $(`#${input_id}_preview`).attr('src', '');
            $(`#${input_id}_preview`).parent().hide();
        }

        $("#form-insert").submit(function(e) {
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
                _t.find('button[type="submit"]').html('<i class="bi bi-pencil-square"></i> Adicionar');
            });
        });
    });
</script>