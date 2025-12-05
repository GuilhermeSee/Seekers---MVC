<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Chat: <?= htmlspecialchars($sessao['jogo']) ?></h4>
                    <small class="text-muted">
                        <?= htmlspecialchars($sessao['tipo_sessao']) ?> - <?= htmlspecialchars($sessao['plataforma']) ?> 
                        | Criado por: <?= htmlspecialchars($sessao['criador']) ?>
                    </small>
                </div>
                <div class="card-body">
                    <div id="chat-messages" style="height: 400px; overflow-y: auto; border: 1px solid #4a4a6a; padding: 15px; margin-bottom: 20px; background: #1a1a2e;">
                        <?php if(empty($mensagens)): ?>
                            <p class="text-muted text-center">Nenhuma mensagem ainda. Seja o primeiro a falar!</p>
                        <?php else: ?>
                            <?php foreach($mensagens as $msg): ?>
                                <?php if(isset($msg['tipo']) && $msg['tipo'] == 'sistema'): ?>
                                    <div class="mb-2 text-center">
                                        <small class="text-warning bg-dark px-2 py-1 rounded">
                                            🔔 <?= htmlspecialchars($msg['mensagem']) ?> - <?= date('H:i', strtotime($msg['data_envio'])) ?>
                                        </small>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3 <?= $msg['usuario_id'] == $_SESSION['usuarioLogado'] ? 'text-end' : '' ?>">
                                        <div class="d-inline-block p-2 rounded <?= $msg['usuario_id'] == $_SESSION['usuarioLogado'] ? 'bg-primary' : 'bg-secondary' ?>" style="max-width: 70%;">
                                            <strong><?= htmlspecialchars($msg['username']) ?>:</strong><br>
                                            <?= nl2br(htmlspecialchars($msg['mensagem'])) ?>
                                            <br><small class="text-light" style="opacity: 0.8;"><?= date('H:i', strtotime($msg['data_envio'])) ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <form method="post" id="chatForm">
                        <div class="input-group">
                            <input type="text" class="form-control" name="mensagem" id="mensagemInput" placeholder="Digite sua mensagem..." required autocomplete="off">
                            <button type="submit" class="btn btn-primary" id="enviarBtn">Enviar</button>
                        </div>
                    </form>

                    <div class="mt-3">
                        <a href="<?= BASE_URL ?>/sessao_detalhes?id=<?= $sessao['id'] ?>" class="btn btn-outline-primary">Ver Detalhes da Sessão</a>
                        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">Voltar ao Perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-scroll para a última mensagem
document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;

let ultimaAtualizacao = new Date().getTime();

// Função para carregar mensagens via AJAX
function carregarMensagens() {
    $.get('<?= BASE_URL ?>/public/ajax/carregar_mensagens.php', {
        sessao_id: <?= $sessao['id'] ?>,
        ultima_atualizacao: ultimaAtualizacao
    }, function(data) {
        if(data.mensagens && data.mensagens.length > 0) {
            data.mensagens.forEach(function(msg) {
                let messageHtml;
                if(msg.tipo === 'sistema') {
                    messageHtml = `
                        <div class="mb-2 text-center">
                            <small class="text-warning bg-dark px-2 py-1 rounded">
                                🔔 ${msg.mensagem} - ${msg.horario}
                            </small>
                        </div>
                    `;
                } else {
                    const isOwn = msg.usuario_id == <?= $_SESSION['usuarioLogado'] ?>;
                    messageHtml = `
                        <div class="mb-3 ${isOwn ? 'text-end' : ''}">
                            <div class="d-inline-block p-2 rounded ${isOwn ? 'bg-primary' : 'bg-secondary'}" style="max-width: 70%;">
                                <strong>${msg.username}:</strong><br>
                                ${msg.mensagem.replace(/\n/g, '<br>')}
                                <br><small class="text-light" style="opacity: 0.8;">${msg.horario}</small>
                            </div>
                        </div>
                    `;
                }
                $('#chat-messages').append(messageHtml);
            });
            
            // Auto-scroll
            document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
            ultimaAtualizacao = new Date().getTime();
        }
    }, 'json');
}

// Recarregar mensagens a cada 3 segundos
setInterval(carregarMensagens, 3000);

// Atualizar contador de notificações no header
setInterval(function() {
    $.get('<?= BASE_URL ?>/public/ajax/contar_notificacoes.php', function(data) {
        if(data.count > 0) {
            if($('.navbar .badge').length === 0) {
                $('.navbar-nav .nav-link[href*="notificacoes"]').append('<span class="badge bg-danger">' + data.count + '</span>');
            } else {
                $('.navbar .badge').text(data.count).show();
            }
        } else {
            $('.navbar .badge').hide();
        }
    }, 'json');
}, 5000);

// Focar no campo de mensagem
$('#mensagemInput').focus();

// Enviar mensagem via AJAX
$('#chatForm').on('submit', function(e) {
    e.preventDefault();
    
    const mensagem = $('#mensagemInput').val().trim();
    if(mensagem) {
        $('#enviarBtn').prop('disabled', true);
        
        $.post('<?= BASE_URL ?>/public/ajax/enviar_mensagem.php', {
            sessao_id: <?= $sessao['id'] ?>,
            mensagem: mensagem
        }, function(response) {
            if(response.success) {
                $('#mensagemInput').val('').focus();
                setTimeout(carregarMensagens, 500);
            } else {
                alert('Erro ao enviar mensagem: ' + (response.error || 'Erro desconhecido'));
            }
            $('#enviarBtn').prop('disabled', false);
        }, 'json').fail(function() {
            alert('Erro de conexão');
            $('#enviarBtn').prop('disabled', false);
        });
    }
    return false;
});

// Enter para enviar
$('#mensagemInput').on('keypress', function(e) {
    if(e.which === 13) {
        $('#chatForm').submit();
        return false;
    }
});
</script>