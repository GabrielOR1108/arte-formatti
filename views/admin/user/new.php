<?php $this->layout('admin/master'); ?>
<style>
    .cropper-crop-box,
    .cropper-view-box {
        border-radius: 50%;
    }

    .cropper-view-box {
        box-shadow: 0 0 0 2px #6667ab;
        outline: 0;
    }
</style>

<div class="container px-5">
    <div class="mt-3 mb-2 d-flex">
        <h4><i class="bi bi-person-add"></i> Cadastrando novo Usuário</h4>
        <a class="btn border ms-auto" href="<?= $router->route('admin.user.users') ?>"><i class="bi bi-arrow-left"></i> Voltar</a>
    </div>

    <form class="pb-5" id="form-new-user" action="<?= $router->route('admin.user.create') ?>" method="POST" enctype="multipart/form-data">
        <div class="row col-12 mb-3">
            <div class="col-xl-6 col-md-6 col-sm-12 mb-3">
                <label for="first_name" class="form-label">Nome:</label>
                <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Nome" required>
            </div>

            <div class="col-xl-6 col-md-6 col-sm-12 mb-3">
                <label for="last_name" class="form-label">Sobrenome:</label>
                <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Sobrenome" required>
            </div>

            <div class="col-xl-8 col-md-8 col-sm-12 mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
            </div>

            <div class="col-xl-4 col-md-4 col-sm-12 mb-3">
                <label for="password" class="form-label">Senha:</label>
                <div class="input-group">
                    <span class="input-group-text" style="cursor: pointer;"><i class="bi bi-eye"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Senha" required>
                </div>
            </div>

            <div class="col-xl-7 col-md-7 col-sm-12 mb-3">
                <label for="level" class="form-label">Nível de usuário:</label>
                <select name="level" id="level" class="form-select" required>
                    <?php foreach ($user_levels as $level) : ?>
                        <option value="<?= $this->e($level['id']); ?>"><?= $this->e($level['level']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-xl-1 col-md-1 col-sm-12 mb-3">
                <label for="active" class="form-label">Ativo:</label>
                <div class="form-check form-switch form-switch-lg">
                    <input class="form-check-input" type="checkbox" value="1" role="switch" id="active" name="active">
                </div>
            </div>

            <div class="col-xl-4 col-md-4 col-sm-12 mb-3">
                <label for="image" class="form-label">Foto de perfil:</label>
                <input type="file" accept="image/*" class="form-control" id="image" placeholder="Imagem" data-width="<?= $this->e($img_width) ?>" data-height="<?= $this->e($img_height) ?>" required>
            </div>

            <div class="row flex-lg-row-reverse flex-md-row-reverse flex-sm-column">
                <div class="col-xl-4 col-md-4 col-sm-12 mb-5">
                    <div class="w-100" style="display: none;">
                        <img id="image_preview" class="w-100 mt-3 rounded-circle border">
                    </div>
                </div>

                <div class="col-xl-8 col-md-8 col-sm-12 mb-5">
                    <button type="submit" class="btn btn-outline-success"><i class="bi bi-person-add"></i> Cadastrar</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="profilePicModal" tabindex="-1" aria-labelledby="profilePicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-height: 50vh; height: 50vh;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="profilePicModalLabel"><i class="bi bi-person-bounding-box"></i> Recorte sua nova foto de perfil</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 text-center">
                    <img id="image_demo" style="max-width: 100%; max-height: 60vh; display: block;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="image_crop" class="btn w-100 btn-outline-success"><i class="bi bi-cloud-arrow-up"></i> Alterar imagem</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var $modal = $("#profilePicModal");
        var $image = $("#image_demo");
        var cropper, image_blob, img_width, img_height, images_blobs = {};


        $("#image").change(function(e) {
            var files = e.target.files;
            img_width = $(this).data('width');
            img_height = $(this).data('height');
            var done = function(url) {
                $image.attr('src', url);
                $modal.modal('show');
            }

            if (files && files.length > 0) {
                reader = new FileReader();
                reader.onload = function(event) {
                    done(reader.result);
                };
                reader.readAsDataURL(files[0]);
            }
        });

        $modal.on('shown.bs.modal', function() {
            cropper = new Cropper($image[0], {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                guides: false,
                center: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
            $("#upload_avatar").val(null);
        });

        $("#image_crop").click(function() {
            var _preview = $(`#image_preview`);

            canvas = cropper.getCroppedCanvas({
                width: img_width,
                height: img_height
            });
            canvas.toBlob(function(blob) {
                let imageUrl = URL.createObjectURL(blob);
                _preview.attr('src', imageUrl);
                images_blobs['image'] = (blob);
            });
            _preview.parent().show();
            $modal.modal('hide');
        });

        $("#form-new-user").submit(function(e) {
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

            var redirect_url, res_status;
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
                    res_status = res.icon;
                    Swal.fire({
                        allowOutsideClick: false,
                        icon: (res.icon) ? res.icon : 'error',
                        title: (res.title) ? res.title : 'Oops...',
                        text: (res.message) ? res.message : 'Ocorreu um erro.',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (res_status == 'success') {
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
    })
</script>