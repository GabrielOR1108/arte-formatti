<?php $this->layout('admin/master') ?>
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

<div class="container">
    <div class="d-flex col-12 my-4">
        <h4>Meu Perfil</h4>
        <button onclick="history.back()" class="btn border ms-auto"><i class="bi bi-arrow-left"></i> Voltar</button>
    </div>
    <div class="row align-items-start col-12 flex-column flex-sm-row" style="margin-left: 0;">

        <div class="nav flex-column nav-pills me-xl-3 nav-user col-xl-2 col-md-2 col-sm-12 mb-4" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="bi bi-person"></i> Perfil</button>
            <button class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab" aria-controls="v-pills-security" aria-selected="false"><i class="bi bi-lock"></i> Segurança</button>
            <button class="nav-link" id="v-account-tab" data-bs-toggle="pill" data-bs-target="#v-account" type="button" role="tab" aria-controls="v-account" aria-selected="false"><i class="bi bi-gear"></i> Conta</button>
        </div>

        <div class="tab-content col-xl-9 col-md-9 col-sm-12" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
                <div class="row col-12">
                    <div class="col-xl-9 col-md-9 col-sm-12 pe-4">
                        <form id="form-profile" action="<?= $router->route('admin.user.update') ?>" method="POST">
                            <label for="Nome" class="form-label">Nome:</label>
                            <div class="input-group mb-3">
                                <input type="text" name="first_name" class="form-control" value="<?= $this->e($first_name) ?>" placeholder="Nome" aria-label="Nome" aria-describedby="basic-addon1" required>
                            </div>

                            <label for="Sobrenome" class="form-label">Sobrenome:</label>
                            <div class="input-group mb-3">
                                <input type="text" name="last_name" class="form-control" value="<?= $this->e($last_name) ?>" placeholder="Sobrenome" aria-label="Sobrenome" aria-describedby="basic-addon1" required>
                            </div>

                            <label for="Email" class="form-label">Email:</label>
                            <div class="input-group mb-4">
                                <input type="email" name="email" class="form-control" value="<?= $this->e($email) ?>" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" required>
                            </div>

                            <div class="w-100 d-flex mb-4">
                                <button type="submit" id="btn-form-profile" class="btn btn-outline-success ms-auto"><i class="bi bi-pencil-square"></i> Atualizar perfil</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-3 col-md-3 col-sm-12 user-pfp-area">
                        <label class="mb-1"><small class="text-muted">Foto de perfil</small></label>
                        <img src="uploads/mw_users/images/<?= $this->e($user_avatar); ?>" alt="<?= $this->e($first_name); ?>" class="rounded-circle border w-100 mb-2" srcset="">
                        <div class="mt-n4">
                            <input type="file" id="upload_avatar" name="upload_avatar" accept="image/*">
                            <label for="upload_avatar" id="upload_avatar_label" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Alterar</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade col-12" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab" tabindex="0">
                <form id="form-password" action="<?= $router->route('admin.user.updatepass') ?>" method="POST">
                    <label class="form-label">Senha antiga:</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="cursor: pointer;"><i class="bi bi-eye"></i></span>
                        <input type="password" name="old_password" class="form-control" placeholder="Senha antiga" aria-label="Senha antiga" aria-describedby="basic-addon1" required>
                    </div>
                    <label class="form-label">Nova senha:</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="cursor: pointer;"><i class="bi bi-eye"></i></span>
                        <input type="password" name="new_password" class="form-control" placeholder="Nova senha" aria-label="Nova senha" aria-describedby="basic-addon1" required>
                    </div>
                    <label class="form-label">Repita a nova senha:</label>
                    <div class="input-group mb-4">
                        <span class="input-group-text" style="cursor: pointer;"><i class="bi bi-eye"></i></span>
                        <input type="password" name="repeat_new_password" class="form-control" placeholder="Repita nova senha" aria-label="Repita nova senha" aria-describedby="basic-addon1" required>
                    </div>
                    <div class="d-flex">
                        <button type="submit" id="btn-form-password" class="btn btn-outline-success ms-auto"><i class="bi bi-pencil-square"></i> Atualizar senha</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="v-account" role="tabpanel" aria-labelledby="v-account-tab" tabindex="0">
                <h3 class="text-danger mb-4"> Excluir conta</h3>
                <p class="text-muted mb-4">Tem certeza que deseja excluir sua conta? Todos seus dados serão perdidos e essa ação não poderá ser desfeita.</p>
                <p>
                    <a class="btn btn-outline-danger" id="btn-delete-profile" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Sim, quero excluir minha conta
                    </a>
                </p>
                <div class="collapse" id="collapseExample">

                    <form action="<?= $router->route('admin.user.delete') ?>" method="POST" id="form-delete">
                        <p class="form-label text-muted fs-6">Digite sua senha para confirmar a exclusão da conta:</p>
                        <div class="input-group mb-4">
                            <span class="input-group-text" style="cursor: pointer;"><i class="bi bi-eye"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Senha atual" aria-label="Senha atual" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-danger ms-auto"><i class="bi bi-trash"></i> Excluir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                    <img id="avatar_demo" style="max-width: 100%; max-height: 60vh; display: block;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="avatar_crop" class="btn w-100 btn-outline-success"><i class="bi bi-cloud-arrow-up"></i> Alterar imagem</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var $modal = $("#profilePicModal");
        var $image = $("#avatar_demo");
        var cropper, image_blob;


        $("#upload_avatar").change(function(e) {
            var files = e.target.files;

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

        $("#avatar_crop").click(function() {
            canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500
            });
            canvas.toBlob(function(blob) {
                let imageUrl = URL.createObjectURL(blob);
                image_blob = (blob);

                var formData = new FormData();
                formData.append('image', image_blob);
                $.ajax({
                    url: '<?= $router->route('admin.user.updateavatar') ?>',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: 'Foto de perfil atualizada com sucesso!',
                                showConfirmButton: false,
                            })
                            window.location.reload();
                        }
                    }
                })
            });
            $modal.modal('hide');
        });
    })

    // Form senha
    $('#form-profile').submit(function(e) {
        $('#btn-form-profile').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                Swal.fire({
                    icon: (res.icon) ? res.icon : 'error',
                    title: (res.title) ? res.title : 'Oops...',
                    text: (res.message) ? res.message : 'Ocorreu um erro.',
                });
                $('#form-profile').each(function() {
                    if (res.icon == 'success') {
                        window.location.reload()
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
            $('#btn-form-profile').html('<i class="bi bi-pencil-square"></i> Atualizar perfil');
        });
    });

    $('#form-password').submit(function(e) {
        $('#btn-form-password').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                Swal.fire({
                    icon: (res.icon) ? res.icon : 'error',
                    title: (res.title) ? res.title : 'Oops...',
                    text: (res.message) ? res.message : 'Ocorreu um erro.',
                });
                $('#form-password').each(function() {
                    if (res.icon == 'success') {
                        this.reset();
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
            $('#btn-form-password').html('<i class="bi bi-pencil-square"></i> Atualizar senha');
        });
    });

    $("#btn-delete-profile").on('click', function() {
        var _t = $(this);

        if (_t.hasClass('btn-outline-danger')) {
            _t.removeClass('btn-outline-danger');
            _t.addClass('btn-secondary');
            _t.text('Não quero excluir minha conta');
        } else {
            _t.removeClass('btn-secondary');
            _t.addClass('btn-outline-danger');
            _t.text('Sim, quero excluir minha conta');
        }
    })

    $("#form-delete").submit(function(e) {
        e.preventDefault();

        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: "Você tem certeza que deseja excluir sua conta? Essa ação não será reversível.",
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#b7b7b7',
            confirmButtonText: 'Sim, tenho certeza!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        Swal.fire({
                            icon: (res.icon) ? res.icon : 'error',
                            title: (res.title) ? res.title : 'Oops...',
                            text: (res.message) ? res.message : 'Ocorreu um erro.',
                        });
                        if (res.icon == 'success') {
                            window.location.href = '<?= $router->route('admin.login.index') ?>';
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Algo deu errado!',
                        })
                    }
                })
            }
        })

    })
</script>