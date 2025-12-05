<?php
session_start();
require_once '../../config/database.php';

$termo = isset($_GET['termo']) ? $_GET['termo'] : '';
$jogo = isset($_GET['jogo']) ? $_GET['jogo'] : '';

$conexao = conexao();
$sql = "SELECT b.*, u.username FROM builds b 
        JOIN usuarios u ON b.autor_id = u.id 
        WHERE b.publico = 1";

$params = [];

if(!empty($termo)){
    $sql .= " AND (b.nome LIKE :termo OR b.descricao LIKE :termo)";
    $params[':termo'] = "%$termo%";
}

if(!empty($jogo)){
    $sql .= " AND b.jogo = :jogo";
    $params[':jogo'] = $jogo;
}

$sql .= " ORDER BY b.criado_em DESC";

$stmt = $conexao->prepare($sql);
foreach($params as $key => $value){
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$builds = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <?php if(empty($builds)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                Nenhuma build encontrada com os critérios de busca.
            </div>
        </div>
    <?php else: ?>
        <?php foreach($builds as $build): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card build-card h-100 <?= isset($_SESSION['usuarioLogado']) && $build['autor_id'] == $_SESSION['usuarioLogado'] ? 'border-warning' : '' ?>">
                    <div class="card-body">
                        <?php if(isset($_SESSION['usuarioLogado']) && $build['autor_id'] == $_SESSION['usuarioLogado']): ?>
                            <span class="badge bg-warning text-dark mb-2">Sua Build</span>
                        <?php endif; ?>
                        <h5 class="card-title"><?= htmlspecialchars($build['nome']) ?></h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <strong><?= htmlspecialchars($build['jogo']) ?></strong><br>
                                Classe: <?= htmlspecialchars($build['classe']) ?> | Nível: <?= $build['nivel'] ?><br>
                                Por: <?= htmlspecialchars($build['username']) ?>
                            </small>
                        </p>
                        <p class="card-text"><?= htmlspecialchars(substr($build['descricao'], 0, 100)) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= BASE_URL ?>/build_detalhes?id=<?= $build['id'] ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                            <div>
                                <?php if(isset($_SESSION['usuarioLogado'])): ?>
                                    <?php
                                    $sql_curtiu = "SELECT id FROM curtidas_builds WHERE usuario_id = :usuario_id AND build_id = :build_id";
                                    $stmt_curtiu = $conexao->prepare($sql_curtiu);
                                    $stmt_curtiu->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
                                    $stmt_curtiu->bindParam(':build_id', $build['id']);
                                    $stmt_curtiu->execute();
                                    $ja_curtiu = $stmt_curtiu->fetch();
                                    ?>
                                    <button class="btn <?= $ja_curtiu ? 'btn-success' : 'btn-outline-primary' ?> btn-sm" id="btn-curtir-<?= $build['id'] ?>" onclick="curtirBuild(<?= $build['id'] ?>)">
                                        ❤️ <span id="curtidas-<?= $build['id'] ?>"><?= $build['curtidas'] ?></span> <?= $ja_curtiu ? 'Curtido' : 'Curtir' ?>
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">❤️ <?= $build['curtidas'] ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>