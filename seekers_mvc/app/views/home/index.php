<div class="hero-section">
    <div class="container">
        <h1>SEEKERS</h1>
        <p class="lead">A plataforma definitiva para jogadores de jogos souls-like</p>
        <p>Conecte-se com outros jogadores, compartilhe builds e conquiste os desafios mais difíceis juntos</p>
        <?php if(!isset($_SESSION['usuarioLogado'])): ?>
            <a href="<?= BASE_URL ?>/cadastro" class="btn btn-primary btn-lg me-3">Junte-se à Comunidade</a>
            <a href="<?= BASE_URL ?>/login" class="btn btn-outline-light btn-lg">Fazer Login</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/dashboard" class="btn btn-primary btn-lg">Acessar Perfil</a>
        <?php endif; ?>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">Sessões Personalizadas</h5>
                    <p class="card-text">Encontre parceiros compatíveis por plataforma, jogo e estilo de sessão</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">Builds Personalizadas</h5>
                    <p class="card-text">Crie, compartilhe e descubra builds otimizadas para cada situação</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">Chat com IA Especializada</h5>
                    <p class="card-text">Converse com um assistente especializado em jogos souls-like para tirar dúvidas sobre builds, estratégias e dicas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <h3 class="text-warning mb-4">Builds Recentes</h3>
            <?php foreach($builds_recentes as $build): ?>
                <div class="card mb-3 build-card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($build['nome']) ?></h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <?= htmlspecialchars($build['jogo']) ?> - Nível <?= $build['nivel'] ?> 
                                por <?= htmlspecialchars($build['username']) ?>
                            </small>
                        </p>
                        <p class="card-text"><?= htmlspecialchars(substr($build['descricao'], 0, 100)) ?>...</p>
                        <a href="<?= BASE_URL ?>/build_detalhes?id=<?= $build['id'] ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="<?= BASE_URL ?>/builds" class="btn btn-outline-primary">Ver Todas as Builds</a>
        </div>

        <div class="col-md-6">
            <h3 class="text-warning mb-4">Sessões Abertas</h3>
            <?php foreach($sessoes_abertas as $sessao): ?>
                <div class="card mb-3 sessao-card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($sessao['jogo']) ?></h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <?= htmlspecialchars($sessao['plataforma']) ?> - <?= htmlspecialchars($sessao['tipo_sessao']) ?>
                                por <?= htmlspecialchars($sessao['username']) ?>
                            </small>
                        </p>
                        <p class="card-text"><?= htmlspecialchars($sessao['descricao']) ?></p>
                        <span class="badge bg-success status-aberta">Aberta</span>
                        <a href="<?= BASE_URL ?>/sessao_detalhes?id=<?= $sessao['id'] ?>" class="btn btn-primary btn-sm float-end">Participar</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="<?= BASE_URL ?>/sessoes" class="btn btn-outline-primary">Ver Todas as Sessões</a>
        </div>
    </div>
</div>