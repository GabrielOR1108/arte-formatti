<?php $this->layout('admin/master') ?>

<div class="container">
    <div class="d-flex col-12 my-4">
        <h4><i class="bi bi-gear-fill"></i> Configurações</h4>
        <button onclick="history.back()" class="btn border ms-auto"><i class="bi bi-arrow-left"></i> Voltar</button>
    </div>

    <div class="row align-items-start col-12 flex-column flex-sm-row" style="margin-left: 0;">

        <div class="nav flex-column nav-pills nav-user col-xl-2 col-md-2 col-sm-12" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active" id="v-pills-cookies-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cookies" type="button" role="tab" aria-controls="v-pills-cookies" aria-selected="false"><i class="bi bi-qr-code"></i> Cookies</button>
            <button class="nav-link" id="v-pills-recipients-tab" data-bs-toggle="pill" data-bs-target="#v-pills-recipients" type="button" role="tab" aria-controls="v-pills-recipients" aria-selected="false"><i class="bi bi-mailbox"></i> Destinatários</button>
            <button class="nav-link" id="v-pills-email-tab" data-bs-toggle="pill" data-bs-target="#v-pills-email" type="button" role="tab" aria-controls="v-pills-email" aria-selected="false"><i class="bi bi-envelope"></i> Email Layout</button>
            <button class="nav-link" id="v-pills-metatags-tab" data-bs-toggle="pill" data-bs-target="#v-pills-metatags" type="button" role="tab" aria-controls="v-pills-metatags" aria-selected="false"><i class="bi bi-tags"></i> Meta Tags</button>
            <button class="nav-link" id="v-pills-server-tab" data-bs-toggle="pill" data-bs-target="#v-pills-server" type="button" role="tab" aria-controls="v-pills-server" aria-selected="true"><i class="bi bi-database"></i> Servidor SMTP</button>
        </div>

        <div class="tab-content col-xl-9 col-md-9 col-sm-12" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-cookies" role="tabpanel" aria-labelledby="v-pills-cookies-tab" tabindex="0">
                <h5><i class="bi bi-qr-code"></i> Cookies</h5>
                <form id="form-cookies" class="form-submit" action="<?= $router->route('admin.settings.updatecookies') ?>" method="POST">
                    <label class="form-label">Título:</label>
                    <div class="input-group mb-3">
                        <input type="text" name="title" value="<?= $this->e($cookies['title']); ?>" class="form-control" placeholder="Título" aria-label="Título" aria-describedby="basic-addon1" required>
                    </div>
                    <label class="form-label">Texto:</label>
                    <div class="input-group mb-3">
                        <textarea class="form-control" name="text" placeholder="Texto" rows="6" required><?= $this->e($cookies['text']); ?></textarea>
                    </div>

                    <div class="d-flex mb-4">
                        <button type="submit" class="btn btn-outline-success ms-auto"><i class="bi bi-pencil-square"></i> Salvar</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="v-pills-recipients" role="tabpanel" aria-labelledby="v-pills-recipients-tab" tabindex="0">
                <h5><i class="bi bi-mailbox"></i> Destinatários</h5>
                <div class="table-responsive">
                    <table id="dataTable" class="table table-bordered table-hover hover caption-top display w-100" style="max-width: 100% !important;">
                        <thead class="border"></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-email" role="tabpanel" aria-labelledby="v-pills-email-tab" tabindex="0">
                <h5><i class="bi bi-envelope"></i> Email Layout</h5>
                <form id="form-email-layout" action="<?= $router->route('admin.settings.updateemaillayout') ?>" method="POST" enctype="multipart/form-data">
                    <label for="top" class="form-label">Cabeçalho:</label>
                    <div class="input-group mb-4">
                        <input class="form-control" accept="image/*" data-width="<?= $this->e($email['dimensions']['top']['width']) ?>" data-height="<?= $this->e($email['dimensions']['top']['height']) ?>" type="file" id="top">
                    </div>
                    <div class="col-12 mb-3" style="display: none;">
                        <span>Preview novo Cabeçalho:</span>
                        <img id="top_preview" class="w-100 border border-2">
                    </div>
                    <div class="col-12 mb-3">
                        <small class="text-muted">Cabeçalho atual:</small>
                        <img src="uploads/mw_email_layout/images/<?= $this->e($email['images']['top']); ?>" class="w-100 border border-2">
                    </div>
                    <label for="bottom" class="form-label">Rodapé:</label>
                    <div class="input-group mb-4">
                        <input class="form-control" accept="image/*" data-width="<?= $this->e($email['dimensions']['bottom']['width']) ?>" data-height="<?= $this->e($email['dimensions']['bottom']['height']) ?>" type="file" id="bottom">
                    </div>
                    <div class="col-12 mb-3" style="display: none;">
                        <span>Preview novo Rodapé:</span>
                        <img id="bottom_preview" class="w-100 border border-2">
                    </div>
                    <div class="col-12 mb-3">
                        <small class="text-muted">Rodapé atual:</small>
                        <img src="uploads/mw_email_layout/images/<?= $this->e($email['images']['bottom']); ?>" class="w-100 border border-2">
                    </div>
                    <div class="d-flex mb-4">
                        <button type="submit" class="btn btn-outline-success ms-auto"><i class="bi bi-pencil-square"></i> Salvar</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="v-pills-metatags" role="tabpanel" aria-labelledby="v-pills-metatags-tab" tabindex="0">
                <h5><i class="bi bi-tags"></i> Meta Tags</h5>
                <form id="form-metatags" class="form-submit" action="<?= $router->route('admin.settings.updatemetatags') ?>" method="POST">
                    <label for="meta-description" class="form-label">Meta Descrição</label>
                    <div class="input-group mb-4">
                        <textarea class="form-control" name="description" placeholder="Meta Descrição" maxlength="160" rows="6"><?= $this->e($metatags['description']); ?></textarea>
                    </div>
                    <label for="meta-keywords" class="form-label">Palavras Chaves</label>
                    <div class="input-group mb-4">
                        <textarea class="form-control" name="keywords" placeholder="Palavras Chaves" rows="6"><?= $this->e($metatags['keywords']); ?></textarea>
                    </div>

                    <div class="d-flex mb-4">
                        <button type="submit" class="btn btn-outline-success ms-auto"><i class="bi bi-pencil-square"></i> Salvar</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="v-pills-server" role="tabpanel" aria-labelledby="v-pills-server-tab" tabindex="0">
                <h5><i class="bi bi-database"></i> Servidor SMTP</h5>

                <form id="form-smtp-config" class="form-submit" action="<?= $router->route('admin.settings.updatesmtpconfig') ?>" method="POST">
                    <div class="row col-12">

                        <label for="Servidor" class="form-label">Servidor:</label>
                        <div class="input-group mb-3">
                            <input type="text" name="host" class="form-control" value="<?= $this->e($smtp['host']); ?>" placeholder="Servidor" aria-label="Servidor" aria-describedby="basic-addon1" required>
                        </div>

                        <label for="Usuário" class="form-label">Usuário:</label>
                        <div class="input-group mb-3">
                            <input type="text" name="user" class="form-control" value="<?= $this->e($smtp['user']); ?>" placeholder="Usuário" aria-label="Usuário" aria-describedby="basic-addon1" required>
                        </div>

                        <label for="Senha" class="form-label">Senha:</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text border" style="cursor: pointer;"><i class="bi bi-eye"></i></span>
                            <input type="password" name="pass" class="form-control" value="<?= $this->e($smtp['pass']); ?>" placeholder="Senha" aria-label="Senha" aria-describedby="basic-addon1" required>
                        </div>


                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                            <label for="Nome do Usuário" class="form-label">Nome do Usuário:</label>
                            <div class="input-group mb-3">
                                <input type="text" name="name" class="form-control" value="<?= $this->e($smtp['name']); ?>" placeholder="Nome do Usuário" aria-label="Nome do Usuário" aria-describedby="basic-addon1" required>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                            <label for="auth" class="form-label">Autenticação:</label>
                            <select class="form-select" name="auth" required>
                                <option value="SSL" <?= ($smtp['auth'] == 'SSL') ? 'selected' : ''; ?>>SSL</option>
                                <option value="TLS" <?= ($smtp['auth'] == 'TLS') ? 'selected' : ''; ?>>TLS</option>
                            </select>
                        </div>

                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                            <label for="port" class="form-label">Porta:</label>
                            <select class="form-select" name="port" required>
                                <option value="25" <?= ($smtp['port'] == '25') ? 'selected' : ''; ?>>25</option>
                                <option value="465" <?= ($smtp['port'] == '465') ? 'selected' : ''; ?>>465</option>
                                <option value="587" <?= ($smtp['port'] == '587') ? 'selected' : ''; ?>>587</option>
                            </select>
                        </div>

                        <div class="w-100 d-flex mb-4">
                            <button type="submit" id="btn-form-smtp-config" class="btn btn-outline-success ms-auto"><i class="bi bi-pencil-square"></i> Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newRecipientModal" tabindex="-1" aria-labelledby="newRecipientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="newRecipientModalLabel"><i class="bi bi-mailbox"></i> Novo Destinatário</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= $router->route('admin.recipients.new') ?>" method="POST" class="form-add-recipient">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome:</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                    </div>
                    <div class="d-flex">
                        <button type="submit" id="btn-edit-recipient" class="btn btn-outline-success ms-auto"><i class="bi bi-pencil-square"></i> Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editRecipientModal" tabindex="-1" aria-labelledby="editRecipientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editRecipientModalLabel"><i class="bi bi-mailbox"></i> Editando Destinatário</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= $router->route('admin.recipients.edit') ?>" method="PUT" class="form-edit-recipient">
                    <input type="hidden" name="id" id="recipient_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nome:</label>
                        <input type="text" class="form-control" name="name" id="edit_name" placeholder="Nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="edit_email" placeholder="Email" required>
                    </div>
                    <div class="d-flex">
                        <button type="submit" id="btn-submit-recipient" class="btn btn-outline-success ms-auto"><i class="bi bi-pencil-square"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const $modal_new_recipient = $("#newRecipientModal");
    const $modal_edit_recipient = $("#editRecipientModal");

    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        destroy: true,
        ajax: {
            url: '<?= $router->route('admin.recipients.getrecipients') ?>',
            type: 'GET',
            data: {
                table: 'mw_recipients'
            },
        },
        lengthMenu: [
            [10],
            [10],
        ],
        deferRender: true,
        responsive: true,
        rowId: 'id',
        select: {
            style: 'multi',
            selector: 'td:first-child i',
        },
        order: [
            [1, 'asc']
        ],
        sDom: '<"my-2"B><"d-lg-flex d-md-flex justify-content-between"if>rt<"table-bottom"p><"clear">',
        columns: <?= $recipients['columns']; ?>,
        columnDefs: [{
            targets: 0,
            render: function(Data, type, full, meta) {
                return '<i class="bi bi-square"></i>';
            },
            responsivePriority: 1,
        }],
        buttons: [{
                text: '<i class="bi bi-plus-lg"></i>',
                action: function() {
                    $modal_new_recipient.modal('show');
                },
                className: 'btn btn-success'
            },
            {
                text: '<i class="bi bi-trash"></i>',
                action: function() {
                    let Ids = [];
                    table.rows('.selected').every(function(rowIdx, tableLoop, rowLoop) {
                        let row = this;
                        Ids.push(row.id());
                    });

                    if (Ids.length == 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sem registros selecionados',
                            text: 'Por favor, selecione os registros que deseja excluir.',
                        });
                        return
                    }
                    Swal.fire({
                        title: 'Atenção!',
                        text: 'Tem certeza que deseja excluir os registros selecionados? Todos os arquivos relacionados também serão excluídos.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, tenho certeza',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        $('.excluir').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '<?= $router->route('admin.registers.delete') ?>',
                                type: 'DELETE',
                                dataType: 'json',
                                data: {
                                    table: '<?= $this->e($recipients['table']); ?>',
                                    data: Ids
                                },
                                success: function(res) {
                                    $('.excluir').html('<i class="bi bi-trash"></i>');
                                    Swal.fire({
                                        icon: (res.icon) ? res.icon : 'error',
                                        title: (res.title) ? res.title : 'Oops...',
                                        text: (res.message) ? res.message : 'Ocorreu um erro.',
                                    });
                                    table.rows('.selected').remove().draw();
                                },
                                error: function() {
                                    $('.excluir').html('<i class="bi bi-trash"></i>');
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Houve um erro ao tentar excluir os registros, tente novamente mais tarde!',
                                    });
                                }
                            });
                        } else {
                            $('.excluir').html('<i class="bi bi-trash"></i>');
                        }
                    });
                },
                className: 'btn btn-danger excluir'
            },
        ],
        language: {
            url: "views/admin/assets/DataTables/Lang/pt-br.json",
            loadingRecords: '',
            processing: '',
        },
        oLanguage: {
            sLoadingRecords: '<span class="spinner-border" role="status" aria-hidden="true"></span>',
            sProcessing: '<span class="spinner-border" role="status" aria-hidden="true"></span>',
        }
    });
    $(document).ready(function() {
        $("#v-pills-recipients-tab").click(function() {
            table.draw();
        })

        $('#dataTable').on('click', '.btn-act', function() {
            var btn = $(this);
            var btn_val = (btn.val() == 0) ? 1 : 0;
            var parent = $(this).parent();
            var id = table.row(parent).id();
            var field = btn.data('field');

            btn.addClass('disabled');
            $.ajax({
                url: '<?= $router->route('admin.registers.activate') ?>',
                type: 'PATCH',
                dataType: 'json',
                data: {
                    table: '<?= $this->e($recipients['table']); ?>',
                    id: id,
                    field: field,
                    value: btn_val
                },
                success: function(res) {
                    Swal.fire({
                        icon: (res.icon) ? res.icon : 'error',
                        title: (res.title) ? res.title : 'Oops...',
                        text: (res.message) ? res.message : 'Ocorreu um erro.',
                    });

                    btn.removeClass('disabled');

                    if (res.icon == 'success') {
                        btn.val(btn_val);
                        btn.children('i').toggleClass("bi-x-lg bi-check-lg");
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

        table.on('click', '#select-all', function() {
            var _t = $(this);
            (_t.hasClass('bi-square')) ? table.rows().select(): table.rows().deselect();
            _t.toggleClass('bi-square bi-check-square');
        });

        table.on('draw', function() {
            if ($('#select-all').hasClass('bi-check-square')) {
                $('#select-all').removeClass('bi-check-square').addClass('bi-square');
            }
        })

        table.on('select deselect', function(e, dt, type, indexes) {
            data = table.rows(indexes).data().toArray();
            $.each(data, function(i, v) {
                $(`tr#${data[i].id}`).find('td:first-child i').toggleClass('bi-square bi-check-square');
            })
        });

        $('.form-add-recipient').submit(function(e) {
            e.preventDefault();
            var _t = $(this);
            var btn = $("#btn-submit-recipient");
            var btn_w = btn.width();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').width(btn_w);
                },
                success: function(res) {
                    Swal.fire({
                        icon: (res.icon) ? res.icon : 'error',
                        title: (res.title) ? res.title : 'Oops...',
                        text: (res.message) ? res.message : 'Ocorreu um erro.',
                    });
                    $modal_new_recipient.modal('hide');
                    table.draw();
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Algo deu errado!',
                    })
                }
            }).then(function() {
                btn.html('<i class="bi bi-pencil-square"></i> Salvar');
            });
        });

        $('.form-edit-recipient').submit(function(e) {
            e.preventDefault();
            var _t = $(this);
            var btn = $("#btn-edit-recipient");
            var btn_w = btn.width();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').width(btn_w);
                },
                success: function(res) {
                    Swal.fire({
                        icon: (res.icon) ? res.icon : 'error',
                        title: (res.title) ? res.title : 'Oops...',
                        text: (res.message) ? res.message : 'Ocorreu um erro.',
                    });
                    $modal_edit_recipient.modal('hide');
                    table.draw();
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Algo deu errado!',
                    })
                }
            }).then(function() {
                btn.html('<i class="bi bi-pencil-square"></i> Salvar');
            });
        });

        $modal_edit_recipient.on('hidden.bs.modal', function() {
            $('.form-edit-recipient')[0].reset();
        })

        $(document).on('click', '.edit-recipient', function() {
            var recipient_id = $(this).data('id');
            $.ajax({
                url: '<?= $router->route('admin.recipients.getrecipient') ?>',
                type: 'GET',
                data: {
                    id: recipient_id
                },
                dataType: 'JSON',
                success: function(res) {
                    $("#recipient_id").val(res.id);
                    $("#edit_name").val(res.name);
                    $("#edit_email").val(res.email);
                    $modal_edit_recipient.modal('show');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Não foi possível encontrar o destinatário. Tente novamente mais tarde',
                    });
                }
            })
        });

        var $modal = $("#image_modal");
        var $image = $("#image_demo");
        var cropper, files, img_width, img_height, input, input_id, images_blobs = {};

        $($('input[accept="image/*"]')).on('change', function(e) {
            files = e.target.files;
            input = $(this);
            input_id = input.attr('id');

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

        $("#form-email-layout").submit(function(e) {
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
                    Swal.fire({
                        icon: (res.icon) ? res.icon : 'error',
                        title: (res.title) ? res.title : 'Oops...',
                        text: (res.message) ? res.message : 'Ocorreu um erro.',
                    })
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Algo deu errado!',
                    })
                }
            }).then(function() {
                _t.find('button[type="submit"]').html('<i class="bi bi-pencil-square"></i> Salvar');
            });
        });
    })
</script>