<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Criar Nova Sessão</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>">
                            <?= $mensagem ?>
                            <?php if($sucesso): ?>
                                <br><a href="<?= BASE_URL ?>/sessoes" class="text-white fw-bold">Ver todas as sessões</a> | 
                                <a href="<?= BASE_URL ?>/dashboard" class="text-white fw-bold">Voltar ao Perfil</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!$sucesso): ?>
                    <form action="<?= BASE_URL ?>/criar_sessao" method="post" id="sessaoForm">
                        <div class="row">
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
                            <div class="col-md-6 mb-3">
                                <label for="plataforma" class="form-label">Plataforma:</label>
                                <select class="form-control" id="plataforma" name="plataforma" required>
                                    <option value="">Selecione a plataforma</option>
                                    <option value="PC">PC</option>
                                    <option value="PlayStation 4">PlayStation 4</option>
                                    <option value="PlayStation 5">PlayStation 5</option>
                                    <option value="Xbox One">Xbox One</option>
                                    <option value="Xbox Series">Xbox Series</option>
                                    <option value="Nintendo Switch">Nintendo Switch</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_sessao" class="form-label">Tipo de Sessão:</label>
                                <select class="form-control" id="tipo_sessao" name="tipo_sessao" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="Cooperativo">Cooperativo</option>
                                    <option value="Boss Fight">Boss Fight</option>
                                    <option value="PvP">PvP</option>
                                    <option value="Exploração">Exploração</option>
                                    <option value="Speedrun">Speedrun</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max_participantes" class="form-label">Máximo de Participantes:</label>
                                <select class="form-control" id="max_participantes" name="max_participantes">
                                    <option value="2">2 jogadores</option>
                                    <option value="3">3 jogadores</option>
                                    <option value="4" selected>4 jogadores</option>
                                    <option value="6">6 jogadores</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Descreva sua sessão, objetivos, requisitos..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="usa_mods" name="usa_mods">
                                <label class="form-check-label" for="usa_mods">
                                    Esta sessão usa mods
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Criar Sessão</button>
                        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">Cancelar</a>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('sessaoForm')?.addEventListener('submit', function(e) {
    if (!validarFormulario('sessaoForm')) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});
</script>