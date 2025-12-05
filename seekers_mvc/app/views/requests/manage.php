<?php $titulo = "Gerenciar Solicitações"; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Solicitações para: <?= htmlspecialchars($sessao['jogo']) ?></h4>
                    <small class="text-muted"><?= htmlspecialchars($sessao['tipo_sessao']) ?> - <?= htmlspecialchars($sessao['plataforma']) ?></small>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert alert-success">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <?php if(empty($solicitacoes)): ?>
                        <div class="alert alert-info">
                            Não há solicitações pendentes para esta sessão.
                        </div>
                    <?php else: ?>
                        <?php foreach($solicitacoes as $solicitacao): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-1"><?= htmlspecialchars($solicitacao['username']) ?></h6>
                                            <small class="text-muted">
                                                Solicitou participação em <?= date('d/m/Y H:i', strtotime($solicitacao['data_solicitacao'])) ?>
                                            </small>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="solicitacao_id" value="<?= $solicitacao['id'] ?>">
                                                <button type="submit" name="acao" value="aceitar" class="btn btn-success btn-sm">
                                                    Aceitar
                                                </button>
                                                <button type="submit" name="acao" value="recusar" class="btn btn-danger btn-sm">
                                                    Recusar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="/seekers_mvc/sessao_detalhes?id=<?= $sessao['id'] ?>" class="btn btn-primary">Ver Sessão</a>
                        <a href="/seekers_mvc/dashboard" class="btn btn-secondary">Voltar ao Perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>