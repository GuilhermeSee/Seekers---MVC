<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Editar Perfil</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert alert-danger">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/perfil" method="post" id="perfilForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($usuario['username']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio:</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3"><?= htmlspecialchars($usuario['bio']) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Plataformas:</label>
                                <div>
                                    <?php $plataformas_disponiveis = ['PC', 'PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series', 'Nintendo Switch']; ?>
                                    <?php foreach($plataformas_disponiveis as $plataforma): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="plataformas[]" value="<?= $plataforma ?>" 
                                                   <?= in_array($plataforma, $plataformas_usuario) ? 'checked' : '' ?>>
                                            <label class="form-check-label"><?= $plataforma ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jogos Preferidos:</label>
                                <div>
                                    <?php $jogos_disponiveis = ['Dark Souls', 'Dark Souls 2', 'Dark Souls 3', 'Elden Ring', 'Bloodborne', 'Sekiro']; ?>
                                    <?php foreach($jogos_disponiveis as $jogo): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="jogos_preferidos[]" value="<?= $jogo ?>" 
                                                   <?= in_array($jogo, $jogos_usuario) ? 'checked' : '' ?>>
                                            <label class="form-check-label"><?= $jogo ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="usa_mods" name="usa_mods" <?= $usuario['usa_mods'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="usa_mods">
                                    Uso mods nos jogos
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>