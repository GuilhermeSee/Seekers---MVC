<?php $titulo = "Notificações"; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-warning">Notificações</h2>
                <a href="/seekers_mvc/notificacoes?marcar_lidas=1" class="btn btn-outline-primary">Marcar Todas como Lidas</a>
            </div>

            <?php if(empty($notificacoes)): ?>
                <div class="alert alert-info">
                    Você não tem notificações.
                </div>
            <?php else: ?>
                <?php foreach($notificacoes as $notificacao): ?>
                    <div class="card mb-3 <?= !$notificacao['lida'] ? 'border-warning' : '' ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title">
                                        <?= htmlspecialchars($notificacao['titulo']) ?>
                                        <?php if(!$notificacao['lida']): ?>
                                            <span class="badge bg-warning text-dark">Nova</span>
                                        <?php endif; ?>
                                    </h6>
                                    <p class="card-text"><?= htmlspecialchars($notificacao['mensagem']) ?></p>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($notificacao['data_criacao'])) ?></small>
                                </div>
                                <div>
                                    <?php if($notificacao['tipo'] == 'solicitacao_participacao'): ?>
                                        <?php 
                                        $dados = json_decode($notificacao['dados_extras'], true);
                                        ?>
                                        <a href="/seekers_mvc/gerenciar_solicitacoes?sessao_id=<?= $dados['sessao_id'] ?>" class="btn btn-primary btn-sm">
                                            Gerenciar
                                        </a>
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