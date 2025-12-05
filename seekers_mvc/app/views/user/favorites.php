<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-warning mb-4">Meus Favoritos</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Builds Favoritas</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($builds_favoritas)): ?>
                        <p class="text-muted">Você ainda não favoritou nenhuma build.</p>
                        <a href="<?= BASE_URL ?>/builds" class="btn btn-outline-primary">Explorar Builds</a>
                    <?php else: ?>
                        <?php foreach($builds_favoritas as $build): ?>
                            <div class="card mb-3 sessao-card">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($build['nome']) ?></h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <?= htmlspecialchars($build['jogo']) ?> - Nível <?= $build['nivel'] ?><br>
                                            Por: <?= htmlspecialchars($build['autor']) ?>
                                        </small>
                                    </p>
                                    <p class="card-text"><?= htmlspecialchars(substr($build['descricao'], 0, 100)) ?>...</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?= BASE_URL ?>/build_detalhes?id=<?= $build['id'] ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                                        <button class="btn btn-outline-danger btn-sm" onclick="toggleFavorito('build', <?= $build['id'] ?>)">❤️ Remover</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Sessões Favoritas</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($sessoes_favoritas)): ?>
                        <p class="text-muted">Você ainda não favoritou nenhuma sessão.</p>
                        <a href="<?= BASE_URL ?>/sessoes" class="btn btn-outline-primary">Explorar Sessões</a>
                    <?php else: ?>
                        <?php foreach($sessoes_favoritas as $sessao): ?>
                            <div class="card mb-3 sessao-card">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($sessao['jogo']) ?></h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <?= htmlspecialchars($sessao['plataforma']) ?> - <?= htmlspecialchars($sessao['tipo_sessao']) ?><br>
                                            Por: <?= htmlspecialchars($sessao['criador']) ?>
                                        </small>
                                    </p>
                                    <p class="card-text"><?= htmlspecialchars($sessao['descricao']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?= BASE_URL ?>/sessao_detalhes?id=<?= $sessao['id'] ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                                        <button class="btn btn-outline-danger btn-sm" onclick="toggleFavorito('sessao', <?= $sessao['id'] ?>)">⭐ Remover</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">Voltar ao Dashboard</a>
        </div>
    </div>
</div>