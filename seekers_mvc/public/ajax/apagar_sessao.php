<?php
session_start();

if(!isset($_SESSION['usuarioLogado']) || !isset($_GET['id'])){
    header("Location: /seekers_mvc/dashboard");
    exit;
}

require_once '../../config/database.php';

$sessao_id = $_GET['id'];
$conexao = conexao();

// Verificar se a sessão pertence ao usuário
$sql = "SELECT id FROM sessoes_jogo WHERE id = :id AND criador_id = :criador_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $sessao_id);
$stmt->bindParam(':criador_id', $_SESSION['usuarioLogado']);
$stmt->execute();

if($stmt->rowCount() > 0){
    // Apagar favoritos relacionados
    $sql = "DELETE FROM sessoes_favoritas WHERE sessao_id = :sessao_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->execute();
    
    // Apagar participantes
    $sql = "DELETE FROM participantes_sessao WHERE sessao_id = :sessao_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->execute();
    
    // Apagar mensagens
    $sql = "DELETE FROM mensagens_sessao WHERE sessao_id = :sessao_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->execute();
    
    // Apagar solicitações
    $sql = "DELETE FROM solicitacoes_participacao WHERE sessao_id = :sessao_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->execute();
    
    // Apagar a sessão
    $sql = "DELETE FROM sessoes_jogo WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $sessao_id);
    $stmt->execute();
}

header("Location: /seekers_mvc/dashboard");
exit;
?>