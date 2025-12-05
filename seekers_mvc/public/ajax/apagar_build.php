<?php
session_start();

if(!isset($_SESSION['usuarioLogado']) || !isset($_GET['id'])){
    header("Location: /seekers_mvc/dashboard");
    exit;
}

require_once '../../config/database.php';

$build_id = $_GET['id'];
$conexao = conexao();

// Verificar se a build pertence ao usuário
$sql = "SELECT id FROM builds WHERE id = :id AND autor_id = :autor_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $build_id);
$stmt->bindParam(':autor_id', $_SESSION['usuarioLogado']);
$stmt->execute();

if($stmt->rowCount() > 0){
    // Apagar favoritos relacionados
    $sql = "DELETE FROM builds_favoritas WHERE build_id = :build_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':build_id', $build_id);
    $stmt->execute();
    
    // Apagar curtidas relacionadas
    $sql = "DELETE FROM curtidas_builds WHERE build_id = :build_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':build_id', $build_id);
    $stmt->execute();
    
    // Apagar a build
    $sql = "DELETE FROM builds WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $build_id);
    $stmt->execute();
}

header("Location: /seekers_mvc/dashboard");
exit;
?>