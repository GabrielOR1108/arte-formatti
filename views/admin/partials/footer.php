<footer>
    <div class="modal" id="image_modal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Recortar foto</h5>
                    <button type="button" class="btn-close" id="image_close_modal" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="col-md-12 text-center" style="max-height: 80vh;">
                        <img src="" id="image_demo" style="max-width: 100%; height: 100%; display: block;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-purple" id="image_crop">Recortar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="image_cancel">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(".bi-eye, .bi-eye-slash").click(function(e) {
            e.preventDefault();

            var icon = $(this);
            var input = $(this).closest('.input-group').find('input[type=password], input[type=text]');

            if (icon.hasClass('bi-eye-slash')) {
                icon.removeClass('bi-eye-slash');
                icon.addClass('bi-eye');
                input.attr('type', 'password');
            } else {
                icon.removeClass('bi-eye');
                icon.addClass('bi-eye-slash');
                input.attr('type', 'text');
            }
        });
    </script>
</footer>