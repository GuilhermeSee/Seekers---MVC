<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="plagiarism" content="Este projeto foi desenvolvido por Guilherme Seemann para a disciplina de Laboratório de Desenvolvimento de Software - IFRS Campus Canoas">
    <title><?= isset($titulo) ? $titulo : 'Seekers' ?> - Plataforma Souls-like</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand text-warning fw-bold" href="<?= BASE_URL ?>/">SEEKERS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/builds">Builds</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/sessoes">Sessões</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/contato">Contato</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['usuarioLogado'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/dashboard">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/favoritos">Favoritos</a>
                        </li>
                        <li class="nav-item">
                            <?php
                            if(isset($_SESSION['usuarioLogado'])) {
                                require_once 'config/database.php';
                                $conexao_header = conexao();
                                $sql_notif = "SELECT COUNT(*) as total FROM notificacoes WHERE usuario_id = :id AND lida = 0";
                                $stmt_notif = $conexao_header->prepare($sql_notif);
                                $stmt_notif->bindParam(':id', $_SESSION['usuarioLogado']);
                                $stmt_notif->execute();
                                $notif_count = $stmt_notif->fetch()['total'];
                            }
                            ?>
                            <a class="nav-link" href="<?= BASE_URL ?>/notificacoes">
                                Notificações <?php if(isset($notif_count) && $notif_count > 0): ?><span class="badge bg-danger"><?= $notif_count ?></span><?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/logout">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/cadastro">Cadastro</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?= $content ?>

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-warning">SEEKERS</h5>
                    <p>Plataforma de conexão para jogadores de jogos souls-like</p>
                </div>
                <div class="col-md-6">
                    <h6>Links Úteis</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>/contato" class="text-light">Fale Conosco</a></li>
                        <li><a href="<?= BASE_URL ?>/builds" class="text-light">Builds da Comunidade</a></li>
                        <li><a href="<?= BASE_URL ?>/sessoes" class="text-light">Sessões Abertas</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2025 Seekers - Desenvolvido por Guilherme Seemann</p>
            </div>
        </div>
    </footer>

    <?php if(isset($_SESSION['usuarioLogado'])): ?>
    <script>
    // Atualizar contador de notificações automaticamente
    setInterval(function() {
        $.get('<?= BASE_URL ?>/public/ajax/contar_notificacoes.php', function(data) {
            const badge = $('.navbar .badge');
            if(data.count > 0) {
                if(badge.length) {
                    badge.text(data.count).show();
                } else {
                    $('a[href*="notificacoes"]').append(' <span class="badge bg-danger">' + data.count + '</span>');
                }
            } else {
                badge.remove();
            }
        }, 'json');
    }, 10000);
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/main.js"></script>
</body>
</html>