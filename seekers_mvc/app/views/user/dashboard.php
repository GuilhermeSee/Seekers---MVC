<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="text-warning mb-0"><?= htmlspecialchars($usuario['username']) ?></h3>
                    <small class="text-muted">Membro desde <?= date('d/m/Y', strtotime($usuario['data_criacao'])) ?></small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <?php if(!empty($usuario['bio'])): ?>
                                <p><strong>Bio:</strong> <?= htmlspecialchars($usuario['bio']) ?></p>
                            <?php endif; ?>
                            <?php 
                            $plataformas = json_decode($usuario['plataformas'], true);
                            $jogos = json_decode($usuario['jogos_preferidos'], true);
                            ?>
                            <?php if(!empty($plataformas)): ?>
                                <p><strong>Plataformas:</strong> <?= implode(', ', $plataformas) ?></p>
                            <?php endif; ?>
                            <?php if(!empty($jogos)): ?>
                                <p><strong>Jogos Preferidos:</strong> <?= implode(', ', $jogos) ?></p>
                            <?php endif; ?>
                            <?php if($usuario['usa_mods']): ?>
                                <span class="badge bg-warning">Usa Mods</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="<?= BASE_URL ?>/perfil" class="btn btn-outline-primary mb-2">Editar Perfil</a>
                            <a href="<?= BASE_URL ?>/favoritos" class="btn btn-outline-warning mb-2">Meus Favoritos</a><br>
                            <small class="text-muted">Último acesso: <?= date('d/m/Y H:i', strtotime($usuario['ultimo_acesso'])) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Estatísticas</h5>
                </div>
                <div class="card-body">
                    <p><strong>Builds Criadas:</strong> <?= count($minhas_builds) ?></p>
                    <p><strong>Sessões Criadas:</strong> <?= count($minhas_sessoes) ?></p>
                    <p><strong>Total de Curtidas:</strong> <?= array_sum(array_column($minhas_builds, 'curtidas')) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="text-white mb-0">Minhas Builds</h5>
                    <a href="<?= BASE_URL ?>/criar_build" class="btn btn-primary btn-sm">Nova Build</a>
                </div>
                <div class="card-body">
                    <?php if(empty($minhas_builds)): ?>
                        <p class="text-muted">Você ainda não criou nenhuma build. <a href="<?= BASE_URL ?>/criar_build" class="text-warning">Criar primeira build</a></p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Jogo</th>
                                        <th>Nível</th>
                                        <th>Curtidas</th>
                                        <th>Criada em</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($minhas_builds as $build): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($build['nome']) ?></td>
                                            <td><?= htmlspecialchars($build['jogo']) ?></td>
                                            <td><?= $build['nivel'] ?></td>
                                            <td>❤️ <?= $build['curtidas'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($build['criado_em'])) ?></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>/build_detalhes?id=<?= $build['id'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>
                                                <a href="<?= BASE_URL ?>/editar_build?id=<?= $build['id'] ?>" class="btn btn-sm btn-outline-warning">Editar</a>
                                                <button class="btn btn-sm btn-outline-danger" onclick="excluirBuild(<?= $build['id'] ?>)">Excluir</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Sessões Participando</h5>
                </div>
                <div class="card-body">
                    <!-- Chat com IA - Sempre visível -->
                    <div class="card mb-3 bg-success border-success">
                        <div class="card-body">
                            <h6 class="card-title">🧝‍♀️ Arauto Esmeralda</h6>
                            <p class="card-text">
                                <small class="text-light">
                                    Olá! Sou Arauto Esmeralda e estou aqui para guiar todos os guerreiros em seu caminho árduo. <br>
                                    Pergunte sobre builds, estratégias, dicas ou qualquer dúvida!
                                </small>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="<?= BASE_URL ?>/chat_ia" class="btn btn-light btn-sm">
                                        🧝‍♀️ Chat com IA
                                    </a>
                                </div>
                                <div>
                                    <small class="text-light">Sempre disponível</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(empty($sessoes_participando)): ?>
                        <p class="text-muted">Você não está participando de nenhuma sessão.</p>
                    <?php else: ?>
                        <?php foreach($sessoes_participando as $sessao): ?>
                            <div class="card mb-3 bg-dark border-primary">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($sessao['jogo']) ?></h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <?= $sessao['criador_id'] == $_SESSION['usuarioLogado'] ? 'Sua sessão' : 'Criada por: ' . htmlspecialchars($sessao['criador']) ?><br>
                                            <?= htmlspecialchars($sessao['tipo_sessao']) ?> - <?= htmlspecialchars($sessao['plataforma']) ?>
                                        </small>
                                    </p>
                                    <div class="mb-2">
                                        <small class="text-info">Participantes (<?= count($sessao['participantes']) ?>):</small><br>
                                        <small class="text-muted">
                                            <?php foreach($sessao['participantes'] as $i => $part): ?>
                                                <?= htmlspecialchars($part['username']) ?><?= $i < count($sessao['participantes']) - 1 ? ', ' : '' ?>
                                            <?php endforeach; ?>
                                        </small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="<?= BASE_URL ?>/chat_sessao?id=<?= $sessao['id'] ?>" class="btn btn-primary btn-sm">
                                                💬 Chat <?php if($sessao['mensagens_nao_lidas'] > 0): ?><span class="badge bg-danger"><?= $sessao['mensagens_nao_lidas'] ?></span><?php endif; ?>
                                            </a>
                                            <a href="<?= BASE_URL ?>/sessao_detalhes?id=<?= $sessao['id'] ?>" class="btn btn-outline-primary btn-sm">Ver</a>
                                        </div>
                                        <div>
                                            <?php if($sessao['criador_id'] == $_SESSION['usuarioLogado']): ?>
                                                <a href="<?= BASE_URL ?>/editar_sessao?id=<?= $sessao['id'] ?>" class="btn btn-outline-warning btn-sm">Editar</a>
                                                <button class="btn btn-outline-danger btn-sm" onclick="excluirSessao(<?= $sessao['id'] ?>)">Excluir</button>
                                            <?php else: ?>
                                                <button class="btn btn-outline-danger btn-sm" onclick="sairSessao(<?= $sessao['id'] ?>)">Sair</button>
                                            <?php endif; ?>
                                        </div>
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
                    <h5 class="text-white mb-0">Meus Favoritos</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-warning">Builds Favoritas</h6>
                    <?php if(empty($builds_favoritas)): ?>
                        <p class="text-muted small">Nenhuma build favoritada</p>
                    <?php else: ?>
                        <?php foreach(array_slice($builds_favoritas, 0, 3) as $build): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-dark rounded">
                                <div>
                                    <strong><?= htmlspecialchars($build['nome']) ?></strong><br>
                                    <small class="text-muted">por <?= htmlspecialchars($build['autor']) ?></small>
                                </div>
                                <div>
                                    <a href="<?= BASE_URL ?>/build_detalhes?id=<?= $build['id'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="toggleFavorito('build', <?= $build['id'] ?>)">❤️</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <h6 class="text-warning mt-3">Sessões Favoritas</h6>
                    <?php if(empty($sessoes_favoritas)): ?>
                        <p class="text-muted small">Nenhuma sessão favoritada</p>
                    <?php else: ?>
                        <?php foreach(array_slice($sessoes_favoritas, 0, 3) as $sessao): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-dark rounded">
                                <div>
                                    <strong><?= htmlspecialchars($sessao['jogo']) ?></strong><br>
                                    <small class="text-muted">por <?= htmlspecialchars($sessao['criador']) ?></small>
                                </div>
                                <div>
                                    <a href="<?= BASE_URL ?>/sessao_detalhes?id=<?= $sessao['id'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="toggleFavorito('sessao', <?= $sessao['id'] ?>)">⭐</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>