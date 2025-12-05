<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['usuarioLogado'])){
    echo json_encode(['count' => 0]);
    exit;
}

$conexao = conexao();
$sql = "SELECT COUNT(*) as total FROM notificacoes WHERE usuario_id = :id AND lida = 0";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $_SESSION['usuarioLogado']);
$stmt->execute();
$result = $stmt->fetch();

echo json_encode(['count' => $result['total']]);
?>