<?php $this->layout('admin/master') ?>

<div class="container-fluid px-xl-5 px-lg-5">
    <div class="mt-3 mb-2">
        <h4><?= "<i class=\"bi bi-$table_icon\"></i> $table_title"; ?></h4>
    </div>

    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover hover caption-top display w-100" style="max-width: 100% !important;">
            <thead class="border"></thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        destroy: true,
        ajax: {
            url: '<?= $router->route('admin.registers.getregisters') ?>',
            type: 'GET',
            data: {
                table: '<?= $this->e($slug); ?>'
            },
        },
        lengthMenu: [
            [10, 25, 50, 100, 99999],
            [10, 25, 50, 100, 'Todos'],
        ],
        deferRender: true,
        responsive: true,
        rowId: 'id',
        select: {
            style: 'multi',
            selector: 'td:first-child i',
        },
        order: [
            [1, 'desc']
        ],
        sDom: '<"my-2"B><"d-lg-flex d-md-flex justify-content-between"if>rt<"table-bottom"lp><"clear">',
        columns: <?= $columns; ?>,
        columnDefs: [{
            targets: 0,
            render: function(Data, type, full, meta) {
                return '<i class="bi bi-square"></i>';
            },
            responsivePriority: 1,
        }],
        buttons: [
            <?php if (!$readonly) : ?> {
                    text: '<i class="bi bi-plus-lg"></i> Novo',
                    action: function(e) {
                        e.preventDefault();
                        $(this).on('click', window.location.href = '<?= $router->route('admin.registers.new', ['table' => $slug]) ?>');
                    },
                    className: 'btn btn-success'
                },
            <?php
            endif;
            if ($export) :
            ?> {
                    extend: 'collection',
                    className: 'btn-purple',
                    text: '<i class="bi bi-file-earmark-arrow-up"></i> Exportar',
                    buttons: [{
                            extend: 'copy',
                            text: '<i class="bi bi-clipboard-check"></i> Copiar',
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                }
                            },
                        },
                        {
                            extend: 'csv',
                            text: '<i class="bi bi-filetype-csv"></i> CSV',
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                }
                            },
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bi bi-filetype-xlsx"></i> Excel',
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                }
                            },
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="bi bi-filetype-pdf"></i> PDF',
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                }
                            },
                        },
                    ],
                },
            <?php endif; ?> {
                text: '<i class="bi bi-trash"></i> Excluir',
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
                                    table: '<?= $this->e($slug); ?>',
                                    data: Ids
                                },
                                success: function(res) {
                                    $('.excluir').html('<i class="bi bi-trash"></i> Excluir');
                                    Swal.fire({
                                        icon: (res.icon) ? res.icon : 'error',
                                        title: (res.title) ? res.title : 'Oops...',
                                        text: (res.message) ? res.message : 'Ocorreu um erro.',
                                    });
                                    table.rows('.selected').remove().draw();
                                },
                                error: function() {
                                    $('.excluir').html('<i class="bi bi-trash"></i> Excluir');
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Houve um erro ao tentar excluir os registros, tente novamente mais tarde!',
                                    });
                                }
                            });
                        } else {
                            $('.excluir').html('<i class="bi bi-trash"></i> Excluir');
                        }
                    });
                },
                className: 'btn btn-danger excluir'
            },
            {
                text: '<i class="bi <?= $this->e($favorite); ?> favorite-icon"></i> Favorito',
                action: function() {
                    $.ajax({
                        url: '<?= $router->route('admin.registers.favorite') ?>',
                        type: 'PATCH',
                        data: {
                            table: '<?= $this->e($slug); ?>',
                            id: <?= $_SESSION['user']['id']; ?>
                        },
                        dataType: 'json',
                        success: function(res) {
                            Swal.fire({
                                icon: (res.icon) ? res.icon : 'error',
                                title: (res.title) ? res.title : 'Oops...',
                                text: (res.message) ? res.message : 'Ocorreu um erro.',
                            });
                            $('.favorite-icon').toggleClass('bi-heart bi-heart-fill');
                            window.location.reload();
                        }
                    });
                },
                className: 'btn btn-favorite'
            }
        ],
        language: {
            url: "views/admin/assets/datatables/lang/pt-br.json",
            loadingRecords: '',
            processing: '',
        },
        oLanguage: {
            sLoadingRecords: '<span class="spinner-border" role="status" aria-hidden="true"></span>',
            sProcessing: '<span class="spinner-border" role="status" aria-hidden="true"></span>',
        }
    });

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
                table: '<?= $this->e($slug); ?>',
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
</script>