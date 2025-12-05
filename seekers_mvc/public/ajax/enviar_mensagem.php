<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['usuarioLogado'])){
    echo json_encode(['success' => false, 'error' => 'Usuário não logado']);
    exit;
}

if(!isset($_POST['sessao_id']) || !isset($_POST['mensagem'])){
    echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
    exit;
}

$sessao_id = $_POST['sessao_id'];
$mensagem = trim($_POST['mensagem']);
$usuario_id = $_SESSION['usuarioLogado'];

if(empty($mensagem)){
    echo json_encode(['success' => false, 'error' => 'Mensagem vazia']);
    exit;
}

try {
    $conexao = conexao();
    
    // Verificar se usuário pode enviar mensagem nesta sessão
    $sql = "SELECT s.id FROM sessoes_jogo s 
            LEFT JOIN participantes_sessao p ON s.id = p.sessao_id AND p.usuario_id = :usuario_id
            WHERE s.id = :sessao_id AND (s.criador_id = :usuario_id2 OR p.usuario_id IS NOT NULL)";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':usuario_id2', $usuario_id);
    $stmt->execute();
    
    if(!$stmt->fetch()){
        echo json_encode(['success' => false, 'error' => 'Sem permissão']);
        exit;
    }
    
    // Inserir mensagem
    $sql = "INSERT INTO mensagens_sessao (sessao_id, usuario_id, mensagem) VALUES (:sessao_id, :usuario_id, :mensagem)";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':mensagem', $mensagem);
    
    if($stmt->execute()){
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erro ao inserir no banco']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erro: ' . $e->getMessage()]);
}
?>