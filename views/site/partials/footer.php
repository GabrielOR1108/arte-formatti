<footer>
    <div class="copyright">
        © <span class="t-year"></span> Arte Formatti, todos os direitos reservados. Desenvolvido por <a href="#" target="_blank">MakeWeb</a> 
    </div>
</footer>

<!-- Modal CADASTRO -->
<div class="modal fade" id="cadastro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cadastroLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="cadastroLabel">Nos envie o seu projeto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="form-box">

                    <form action="" class="form-submit">
                        <input type="hidden" name="Tipo" value="Cadastro">

                        <div class="row">
                            <div class="col-12">
                                <label for="Fnome">Nome</label>
                                <input type="text" name="Nome" id="Fnome" class="form-control" placeholder="Insira seu nome aqui" autocomplete="off" value="" required>
                            </div>

                            <div class="col-12">
                                <label for="Femail">E-mail</label>
                                <input type="email" name="Email" id="Femail" class="form-control" placeholder="Insira o seu e-mail aqui" autocomplete="off" value="" required>
                            </div>

                            <div class="col-12">
                                <label for="Local">Residencial ou Empresarial?</label>
                                <select name="Local" id="Local" class="form-control" required>
                                    <option disabled selected>Escolha</option>
                                    <option value="Residencial">Residencial</option>
                                    <option value="Empresarial">Empresarial</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="Projeto">Anexe o seu projeto</label>
                                <input type="file" name="Projeto" id="Projeto" class="form-control" accept="application/pdf" placeholder="Anexe aqui o seu projeto em PDF" required>
                            </div>

                            <div class="col-12">
                                <label for="Descricao">Descreva o seu projeto</label>
                                <textarea name="Descricao" id="Descricao" rows="5" class="form-control" placeholder="Deixe uma breve descrição sobre o seu projeto" required></textarea>
                            </div>

                            <div class="col-12">
                                <div class="text-center">
                                    <button class="btn btn-page">ENVIAR PROJETO</button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Foto -->
<div class="modal fade" id="portfolioModal" tabindex="-1" aria-labelledby="portfolioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="portfolioModalLabel">Foto Portfólio</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <img src="https://picsum.photos/1920/1080" alt="Foto Portfólio" class="img-complete">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-page" data-bs-dismiss="modal">Fechar</button>
            </div>
            
        </div>
    </div>
</div>