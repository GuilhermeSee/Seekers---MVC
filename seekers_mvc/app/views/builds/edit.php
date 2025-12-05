<?php $titulo = "Editar Build"; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Editar Build: <?= htmlspecialchars($build['nome']) ?></h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>">
                            <?= $mensagem ?>
                            <?php if($sucesso): ?>
                                <br><a href="/seekers_mvc/build_detalhes?id=<?= $build['id'] ?>" class="text-white fw-bold">Ver build</a> | 
                                <a href="/seekers_mvc/dashboard" class="text-white fw-bold">Voltar ao perfil</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!$sucesso): ?>
                    <form action="/seekers_mvc/editar_build?id=<?= $build['id'] ?>" method="post" id="buildForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome da Build:</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($build['nome']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jogo" class="form-label">Jogo:</label>
                                <select class="form-control" id="jogo" name="jogo" required>
                                    <option value="">Selecione o jogo</option>
                                    <option value="Dark Souls" <?= $build['jogo'] == 'Dark Souls' ? 'selected' : '' ?>>Dark Souls</option>
                                    <option value="Dark Souls 2" <?= $build['jogo'] == 'Dark Souls 2' ? 'selected' : '' ?>>Dark Souls 2</option>
                                    <option value="Dark Souls 3" <?= $build['jogo'] == 'Dark Souls 3' ? 'selected' : '' ?>>Dark Souls 3</option>
                                    <option value="Elden Ring" <?= $build['jogo'] == 'Elden Ring' ? 'selected' : '' ?>>Elden Ring</option>
                                    <option value="Bloodborne" <?= $build['jogo'] == 'Bloodborne' ? 'selected' : '' ?>>Bloodborne</option>
                                    <option value="Sekiro" <?= $build['jogo'] == 'Sekiro' ? 'selected' : '' ?>>Sekiro</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="classe" class="form-label">Classe Inicial:</label>
                                <input type="text" class="form-control" id="classe" name="classe" value="<?= htmlspecialchars($build['classe']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nível:</label>
                                <p class="form-control-static text-warning">Calculado automaticamente</p>
                                <small class="text-muted">Nível atual: <?= $build['nivel'] ?></small>
                            </div>
                        </div>

                        <h6 class="text-warning mt-4">Atributos</h6>
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label for="vigor" class="form-label">Vigor:</label>
                                <input type="number" class="form-control" id="vigor" name="vigor" min="1" max="99" value="<?= isset($atributos_build['vigor']) ? $atributos_build['vigor'] : 10 ?>">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="forca" class="form-label">Força:</label>
                                <input type="number" class="form-control" id="forca" name="forca" min="1" max="99" value="<?= isset($atributos_build['forca']) ? $atributos_build['forca'] : 10 ?>">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="destreza" class="form-label">Destreza:</label>
                                <input type="number" class="form-control" id="destreza" name="destreza" min="1" max="99" value="<?= isset($atributos_build['destreza']) ? $atributos_build['destreza'] : 10 ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="inteligencia" class="form-label">Inteligência:</label>
                                <input type="number" class="form-control" id="inteligencia" name="inteligencia" min="1" max="99" value="<?= isset($atributos_build['inteligencia']) ? $atributos_build['inteligencia'] : 10 ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="fe" class="form-label">Fé:</label>
                                <input type="number" class="form-control" id="fe" name="fe" min="1" max="99" value="<?= isset($atributos_build['fe']) ? $atributos_build['fe'] : 10 ?>">
                            </div>
                        </div>

                        <h6 class="text-warning mt-4">Equipamentos</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="arma_principal" class="form-label">Arma Principal:</label>
                                <input type="text" class="form-control" id="arma_principal" name="arma_principal" value="<?= htmlspecialchars(isset($equipamentos_build['arma_principal']) ? $equipamentos_build['arma_principal'] : '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="arma_secundaria" class="form-label">Arma Secundária:</label>
                                <input type="text" class="form-control" id="arma_secundaria" name="arma_secundaria" value="<?= htmlspecialchars(isset($equipamentos_build['arma_secundaria']) ? $equipamentos_build['arma_secundaria'] : '') ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="armadura" class="form-label">Armadura:</label>
                                <input type="text" class="form-control" id="armadura" name="armadura" value="<?= htmlspecialchars(isset($equipamentos_build['armadura']) ? $equipamentos_build['armadura'] : '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="anel1" class="form-label">Anel 1:</label>
                                <input type="text" class="form-control" id="anel1" name="anel1" value="<?= htmlspecialchars(isset($equipamentos_build['anel1']) ? $equipamentos_build['anel1'] : '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="anel2" class="form-label">Anel 2:</label>
                                <input type="text" class="form-control" id="anel2" name="anel2" value="<?= htmlspecialchars(isset($equipamentos_build['anel2']) ? $equipamentos_build['anel2'] : '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4"><?= htmlspecialchars($build['descricao']) ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <button type="button" class="btn btn-danger" onclick="apagarBuild()">Apagar Build</button>
                        <a href="/seekers_mvc/build_detalhes?id=<?= $build['id'] ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function apagarBuild() {
    if(confirm('Tem certeza que deseja apagar esta build? Esta ação não pode ser desfeita.')) {
        if(confirm('CONFIRMAÇÃO FINAL: Apagar build permanentemente?')) {
            window.location.href = '/seekers_mvc/public/ajax/apagar_build.php?id=<?= $build['id'] ?>';
        }
    }
}
</script>