<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Editar Sessão</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>">
                            <?= $mensagem ?>
                            <?php if($sucesso): ?>
                                <br><a href="/seekers_mvc/sessao_detalhes?id=<?= $sessao['id'] ?>" class="text-white fw-bold">Ver sessão</a> | 
                                <a href="/seekers_mvc/dashboard" class="text-white fw-bold">Voltar ao Perfil</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!$sucesso): ?>
                    <form action="/seekers_mvc/editar_sessao?id=<?= $sessao['id'] ?>" method="post">
                        <input type="hidden" name="id" value="<?= $sessao['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jogo" class="form-label">Jogo:</label>
                                <select class="form-control" id="jogo" name="jogo" required>
                                    <option value="">Selecione o jogo</option>
                                    <option value="Dark Souls" <?= $sessao['jogo'] == 'Dark Souls' ? 'selected' : '' ?>>Dark Souls</option>
                                    <option value="Dark Souls 2" <?= $sessao['jogo'] == 'Dark Souls 2' ? 'selected' : '' ?>>Dark Souls 2</option>
                                    <option value="Dark Souls 3" <?= $sessao['jogo'] == 'Dark Souls 3' ? 'selected' : '' ?>>Dark Souls 3</option>
                                    <option value="Elden Ring" <?= $sessao['jogo'] == 'Elden Ring' ? 'selected' : '' ?>>Elden Ring</option>
                                    <option value="Bloodborne" <?= $sessao['jogo'] == 'Bloodborne' ? 'selected' : '' ?>>Bloodborne</option>
                                    <option value="Sekiro" <?= $sessao['jogo'] == 'Sekiro' ? 'selected' : '' ?>>Sekiro</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="plataforma" class="form-label">Plataforma:</label>
                                <select class="form-control" id="plataforma" name="plataforma" required>
                                    <option value="">Selecione a plataforma</option>
                                    <option value="PC" <?= $sessao['plataforma'] == 'PC' ? 'selected' : '' ?>>PC</option>
                                    <option value="PlayStation 4" <?= $sessao['plataforma'] == 'PlayStation 4' ? 'selected' : '' ?>>PlayStation 4</option>
                                    <option value="PlayStation 5" <?= $sessao['plataforma'] == 'PlayStation 5' ? 'selected' : '' ?>>PlayStation 5</option>
                                    <option value="Xbox One" <?= $sessao['plataforma'] == 'Xbox One' ? 'selected' : '' ?>>Xbox One</option>
                                    <option value="Xbox Series" <?= $sessao['plataforma'] == 'Xbox Series' ? 'selected' : '' ?>>Xbox Series</option>
                                    <option value="Nintendo Switch" <?= $sessao['plataforma'] == 'Nintendo Switch' ? 'selected' : '' ?>>Nintendo Switch</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_sessao" class="form-label">Tipo de Sessão:</label>
                                <select class="form-control" id="tipo_sessao" name="tipo_sessao" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="Cooperativo" <?= $sessao['tipo_sessao'] == 'Cooperativo' ? 'selected' : '' ?>>Cooperativo</option>
                                    <option value="Boss Fight" <?= $sessao['tipo_sessao'] == 'Boss Fight' ? 'selected' : '' ?>>Boss Fight</option>
                                    <option value="PvP" <?= $sessao['tipo_sessao'] == 'PvP' ? 'selected' : '' ?>>PvP</option>
                                    <option value="Exploração" <?= $sessao['tipo_sessao'] == 'Exploração' ? 'selected' : '' ?>>Exploração</option>
                                    <option value="Speedrun" <?= $sessao['tipo_sessao'] == 'Speedrun' ? 'selected' : '' ?>>Speedrun</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max_participantes" class="form-label">Máximo de Participantes:</label>
                                <select class="form-control" id="max_participantes" name="max_participantes">
                                    <option value="2" <?= $sessao['max_participantes'] == 2 ? 'selected' : '' ?>>2 jogadores</option>
                                    <option value="3" <?= $sessao['max_participantes'] == 3 ? 'selected' : '' ?>>3 jogadores</option>
                                    <option value="4" <?= $sessao['max_participantes'] == 4 ? 'selected' : '' ?>>4 jogadores</option>
                                    <option value="6" <?= $sessao['max_participantes'] == 6 ? 'selected' : '' ?>>6 jogadores</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?= htmlspecialchars($sessao['descricao']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="usa_mods" name="usa_mods" <?= $sessao['usa_mods'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="usa_mods">
                                    Esta sessão usa mods
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <button type="button" class="btn btn-danger" onclick="apagarSessao()">Apagar Sessão</button>
                        <a href="/seekers_mvc/sessao_detalhes?id=<?= $sessao['id'] ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if(!empty($participantes)): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="text-white mb-0">Gerenciar Participantes</h5>
                </div>
                <div class="card-body">
                    <?php foreach($participantes as $participante): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-dark rounded">
                            <span><?= htmlspecialchars($participante['username']) ?></span>
                            <button class="btn btn-sm btn-danger" onclick="removerParticipante(<?= $participante['usuario_id'] ?>)">
                                Remover
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function removerParticipante(usuarioId) {
    if(confirm('Tem certeza que deseja remover este participante?')) {
        $.post('/seekers_mvc/public/ajax/remover_participante.php', {
            sessao_id: <?= $sessao['id'] ?>,
            usuario_id: usuarioId
        }, function(data) {
            if(data.success) {
                location.reload();
            } else {
                alert('Erro ao remover participante');
            }
        }, 'json').fail(function() {
            alert('Erro de conexão ao remover participante');
        });
    }
}

function apagarSessao() {
    if(confirm('Tem certeza que deseja apagar esta sessão? Esta ação não pode ser desfeita.')) {
        if(confirm('CONFIRMAÇÃO FINAL: Apagar sessão permanentemente?')) {
            window.location.href = '/seekers_mvc/public/ajax/apagar_sessao.php?id=<?= $sessao['id'] ?>';
        }
    }
}
</script>