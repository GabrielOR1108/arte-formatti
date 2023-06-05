$(function () {
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.phone').mask('0000-0000');
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.phone_us').mask('(000) 000-0000');
    $('.mixed').mask('AAA 000-S0S');
    $('.cpf').mask('000.000.000-00', { reverse: true });
    $('.cnpj').mask('00.000.000/0000-00', { reverse: true });
    $('.money').mask('000.000.000.000.000,00', { reverse: true });
    $('.money2').mask("#.##0,00", { reverse: true });
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', { reverse: true });
    $('.clear-if-not-match').mask("00/00/0000", { clearIfNotMatch: true });
    $('.placeholder').mask("00/00/0000", { placeholder: "__/__/____" });
    $('.fallback').mask("00r00r0000", {
        translation: {
            'r': {
                pattern: /[\/]/,
                fallback: '/'
            },
            placeholder: "__/__/____"
        }
    });
    $('.selectonfocus').mask("00/00/0000", { selectOnFocus: true });

    var SPMaskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
        spOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

    $('.cel-field').mask(SPMaskBehavior, spOptions);

    $(".tel-0800").mask("0000 000 0000");

    /* Input de endereço */
    $(".cep-field").mask('00000-000');
    $('.cep-field').blur(function () {
        var cep = $(this).val();
        getAddress(cep, $(this).closest('fieldset'));
    });

    function clear_cep() {
        $('form.form-register').find(".fill-rua").val('').blur();
        $('form.form-register').find(".fill-bairro").val('').blur();
        $('form.form-register').find(".fill-cidade").val('').blur();
        $('form.form-register').find(".fill-uf").val('').blur();
    }

    /** Preenche CEP */
    function getAddress(cep) {
        clear_cep($(this));
        cep = cep.replace(/\D/g, '');
        if (cep != "") {
            var validacep = /^[0-9]{8}$/;
            if (validacep.test(cep)) {
                $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
                    if (!("erro" in dados)) {
                        $('form.form-register').find(".fill-rua").val(dados.logradouro).blur();
                        $('form.form-register').find(".fill-bairro").val(dados.bairro).blur();
                        $('form.form-register').find(".fill-cidade").val(dados.localidade).blur();
                        $('form.form-register').find(".fill-uf").val(dados.uf).blur();
                    } else {
                        clear_cep($('form.form-register'));
                        swal("Erro!", "CEP não encontrado.", "error");
                    }
                });
            } else {
                clear_cep($('form.form-register'));
                swal("Erro!", "Formato de CEP inválido.", "error");
            }
        } else {
            clear_cep($('form.form-register'));
        }
    }

    $('.form-submit').submit(function (e) {
        e.preventDefault();
        var _t = $(this);
        var btn = _t.find('button[type=\"submit\"]').html();
        var btn_w = _t.find('button[type=\"submit\"]').width();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function () {
                _t.find('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').width(btn_w);
            },
            success: function (res) {
                Swal.fire({
                    icon: (res.icon) ? res.icon : 'error',
                    title: (res.title) ? res.title : 'Oops...',
                    text: (res.message) ? res.message : 'Ocorreu um erro.',
                });
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Algo deu errado!',
                })
            }
        }).then(function () {
            _t.find('button[type="submit"]').html(btn);
        });
    });

    // Função de gerar url automática nos input de Url
    let _t = $('input[name=url');
    let first_inp = _t.closest('form').find('*').filter(':input:visible:first');
    first_inp.keyup(function () {
        _t.val(generateFriendlyUrl($(this).val()));
    });

    function generateFriendlyUrl(especialChar) {
        especialChar = especialChar.toLowerCase();
        especialChar = especialChar.replace(/[áàãâä]/g, 'a');
        especialChar = especialChar.replace(/[éèêë]/g, 'e');
        especialChar = especialChar.replace(/[íìîï]/g, 'i');
        especialChar = especialChar.replace(/[óòõôö]/g, 'o');
        especialChar = especialChar.replace(/[úùûü]/g, 'u');
        especialChar = especialChar.replace(/[ç]/g, 'c');
        especialChar = especialChar.replace(/[^a-z0-9-]+/g, '-');
        especialChar = especialChar.replace(/\s+/g, '-');
        especialChar = especialChar.replace(/[/]/g, '');
        especialChar = especialChar.replace(/_+/, '-'); //
        especialChar = especialChar.replace(/-+/g, '-');
        especialChar = especialChar.replace(/^[^a-z0-9]/g, '');
        especialChar = especialChar.replace(/[^a-z0-9]$/g, '');
        return especialChar;
    }

    // LOGIN
    $('#form-login').submit(function (e) {
        $('#btn-login').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        e.preventDefault();

        var redirect_url;
        $.ajax({
            url: $(this).attr('action'),
            method: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.icon == 'success') {
                    redirect_url = res.location;
                    Swal.fire({
                        icon: 'success',
                        title: (res.title) ? res.title : 'Oops...',
                        text: (res.message) ? res.message : 'Ocorreu um erro.',
                        timer: 1000,
                        timerProgressBar: true,
                        showLoaderOnConfirm: true,
                        closeOnConfirm: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            const b = Swal.getHtmlContainer().querySelector('b');
                            timerInterval = setInterval(() => {
                                b.textContent = Swal.getTimerLeft();
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((res) => {
                        if (res.dismiss === Swal.DismissReason.timer) {
                            location.href = redirect_url;
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: (res.title) ? res.title : 'Oops...',
                        text: (res.message) ? res.message : 'Ocorreu um erro.'
                    })
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Algo deu errado!',
                })
            }
        }).then(function () {
            $('#btn-login').html('Login');
        });
    });

    $('#eye').click(function (e) {
        e.preventDefault();
        $(this).toggleClass('bi-eye bi-eye-slash');
        $('#password').attr('type', ($(this).hasClass('bi-eye-slash') ? 'text' : 'password'))
    });
});