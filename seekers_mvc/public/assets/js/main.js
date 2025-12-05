// Constante BASE_URL
const BASE_URL = '/seekers_mvc';

// Função para validar formulários
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    for (let input of inputs) {
        if (!input.value.trim()) {
            return false;
        }
    }
    return true;
}

// Função para curtir build
function curtirBuild(buildId) {
    $.post(BASE_URL + '/public/ajax/toggle_curtida.php', {
        build_id: buildId
    }, function(data) {
        if(data.success) {
            const btn = $('#btn-curtir-' + buildId);
            const curtidas = $('#curtidas-' + buildId);
            
            curtidas.text(data.curtidas);
            
            if(data.liked) {
                btn.removeClass('btn-outline-primary').addClass('btn-success');
                btn.html('❤️ <span id="curtidas-' + buildId + '">' + data.curtidas + '</span> Curtido');
            } else {
                btn.removeClass('btn-success').addClass('btn-outline-primary');
                btn.html('❤️ <span id="curtidas-' + buildId + '">' + data.curtidas + '</span> Curtir');
            }
        } else {
            alert('Erro ao curtir build: ' + (data.message || 'Erro desconhecido'));
        }
    }, 'json').fail(function() {
        alert('Erro de conexão');
    });
}

// Função para participar de sessão
function participarSessao(sessaoId) {
    if(confirm('Deseja solicitar participação nesta sessão?')) {
        $.ajax({
            url: BASE_URL + '/public/ajax/solicitar_participacao.php',
            method: 'POST',
            data: { sessao_id: sessaoId },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
            },
            error: function() {
                alert('Erro ao enviar solicitação');
            }
        });
    }
}

// Função para toggle favorito
function toggleFavorito(tipo, itemId) {
    $.post(BASE_URL + '/public/ajax/toggle_favorito.php', {
        tipo: tipo,
        item_id: itemId
    }, function(data) {
        if(data.success) {
            location.reload();
        } else {
            alert('Erro ao alterar favorito');
        }
    }, 'json');
}

// Função para sair da sessão
function sairSessao(sessaoId) {
    if(confirm('Tem certeza que deseja sair desta sessão?')) {
        $.post(BASE_URL + '/public/ajax/sair_sessao.php', {
            sessao_id: sessaoId
        }, function(data) {
            if(data.success) {
                location.reload();
            } else {
                alert('Erro ao sair da sessão: ' + (data.message || 'Erro desconhecido'));
            }
        }, 'json');
    }
}

// Função para excluir build
function excluirBuild(buildId) {
    if(confirm('Tem certeza que deseja excluir esta build?')) {
        if(confirm('CONFIRMAÇÃO FINAL: Excluir build permanentemente?')) {
            window.location.href = BASE_URL + '/public/ajax/apagar_build.php?id=' + buildId;
        }
    }
}

// Função para excluir sessão
function excluirSessao(sessaoId) {
    if(confirm('Tem certeza que deseja excluir esta sessão?')) {
        if(confirm('CONFIRMAÇÃO FINAL: Excluir sessão permanentemente?')) {
            window.location.href = BASE_URL + '/public/ajax/apagar_sessao.php?id=' + sessaoId;
        }
    }
}

// Função para buscar builds
function buscarBuilds() {
    const busca = $('#busca-builds').val();
    const jogo = $('#filtro-jogo').val();
    
    $.post(BASE_URL + '/public/ajax/buscar_builds.php', {
        busca: busca,
        jogo: jogo
    }, function(data) {
        $('#lista-builds').html(data);
    });
}

// Função para verificar notificações
function verificarNotificacoes() {
    $.get(BASE_URL + '/public/ajax/verificar_notificacoes.php', function(data) {
        const count = data.count;
        const badge = $('.navbar-nav .nav-link').find('.badge');
        
        if(count > 0) {
            if(badge.length === 0) {
                $('.navbar-nav .nav-link[href*="notificacoes"]').append('<span class="badge bg-danger">' + count + '</span>');
            } else {
                badge.text(count);
            }
        } else {
            badge.remove();
        }
    }, 'json');
}

// Verificar notificações a cada 5 segundos
$(document).ready(function() {
    if($('.navbar-nav').length > 0) {
        verificarNotificacoes();
        setInterval(verificarNotificacoes, 5000);
    }
});