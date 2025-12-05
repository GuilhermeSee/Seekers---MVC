<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Criar Nova Build</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>">
                            <?= $mensagem ?>
                            <?php if($sucesso): ?>
                                <br><a href="<?= BASE_URL ?>/builds" class="text-white fw-bold">Ver todas as builds</a> | 
                                <a href="<?= BASE_URL ?>/dashboard" class="text-white fw-bold">Voltar ao Perfil</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!$sucesso): ?>
                    <form action="<?= BASE_URL ?>/criar_build" method="post" id="buildForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome da Build:</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jogo" class="form-label">Jogo:</label>
                                <select class="form-control" id="jogo" name="jogo" required>
                                    <option value="">Selecione o jogo</option>
                                    <option value="Dark Souls">Dark Souls</option>
                                    <option value="Dark Souls 2">Dark Souls 2</option>
                                    <option value="Dark Souls 3">Dark Souls 3</option>
                                    <option value="Elden Ring">Elden Ring</option>
                                    <option value="Bloodborne">Bloodborne</option>
                                    <option value="Sekiro">Sekiro</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="classe" class="form-label">Classe Inicial:</label>
                                <input type="text" class="form-control" id="classe" name="classe" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nível:</label>
                                <p class="form-control-static text-warning" id="nivel-calculado">Calculado automaticamente</p>
                                <small class="text-muted">O nível é calculado com base nos atributos</small>
                            </div>
                        </div>

                        <h6 class="text-warning mt-4">Atributos</h6>
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label for="vigor" class="form-label">Vigor:</label>
                                <input type="number" class="form-control" id="vigor" name="vigor" min="1" max="99" value="10">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="forca" class="form-label">Força:</label>
                                <input type="number" class="form-control" id="forca" name="forca" min="1" max="99" value="10">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="destreza" class="form-label">Destreza:</label>
                                <input type="number" class="form-control" id="destreza" name="destreza" min="1" max="99" value="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="inteligencia" class="form-label">Inteligência:</label>
                                <input type="number" class="form-control" id="inteligencia" name="inteligencia" min="1" max="99" value="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="fe" class="form-label">Fé:</label>
                                <input type="number" class="form-control" id="fe" name="fe" min="1" max="99" value="10">
                            </div>
                        </div>

                        <h6 class="text-warning mt-4">Equipamentos</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="arma_principal" class="form-label">Arma Principal:</label>
                                <input type="text" class="form-control" id="arma_principal" name="arma_principal">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="arma_secundaria" class="form-label">Arma Secundária:</label>
                                <input type="text" class="form-control" id="arma_secundaria" name="arma_secundaria">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="armadura" class="form-label">Armadura:</label>
                                <input type="text" class="form-control" id="armadura" name="armadura">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="anel1" class="form-label">Anel 1:</label>
                                <input type="text" class="form-control" id="anel1" name="anel1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="anel2" class="form-label">Anel 2:</label>
                                <input type="text" class="form-control" id="anel2" name="anel2">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Descreva sua build, estratégias, pontos fortes..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Criar Build</button>
                        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">Cancelar</a>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('buildForm')?.addEventListener('submit', function(e) {
    if (!validarFormulario('buildForm')) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});
</script>