<?php
require_once 'BaseController.php';

class RequestController extends BaseController {
    private $sessionModel;
    private $userModel;
    
    public function __construct() {
        $this->sessionModel = new Session();
        $this->userModel = new User();
    }
    
    public function manage() {
        $this->requireAuth();
        
        $sessao_id = $_GET['sessao_id'] ?? 0;
        $sessao = $this->sessionModel->find($sessao_id);
        
        if (!$sessao || $sessao['criador_id'] != $_SESSION['usuarioLogado']) {
            $this->redirect('/dashboard');
            return;
        }
        
        $mensagem = "";
        
        // Processar ações
        if(isset($_POST['acao'])){
            $solicitacao_id = $_POST['solicitacao_id'];
            $acao = $_POST['acao'];
            
            if($acao == 'aceitar'){
                if($this->acceptRequest($solicitacao_id, $sessao_id)){
                    $mensagem = "Solicitação aceita com sucesso!";
                }
            } elseif($acao == 'recusar'){
                if($this->rejectRequest($solicitacao_id, $sessao_id)){
                    $mensagem = "Solicitação recusada.";
                }
            }
        }
        
        // Buscar solicitações pendentes
        $solicitacoes = $this->getPendingRequests($sessao_id);
        
        $this->view('requests/manage', [
            'titulo' => 'Gerenciar Solicitações',
            'sessao' => $sessao,
            'solicitacoes' => $solicitacoes,
            'mensagem' => $mensagem
        ]);
    }
    
    private function acceptRequest($solicitacao_id, $sessao_id) {
        $conexao = $this->sessionModel->getConnection();
        
        try {
            $conexao->beginTransaction();
            
            // Aceitar solicitação
            $sql = "UPDATE solicitacoes_participacao SET status = 'aceita', data_resposta = NOW() WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':id', $solicitacao_id);
            $stmt->execute();
            
            // Buscar solicitante
            $sql = "SELECT solicitante_id FROM solicitacoes_participacao WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':id', $solicitacao_id);
            $stmt->execute();
            $solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Adicionar à tabela participantes_sessao
            $sql = "INSERT IGNORE INTO participantes_sessao (sessao_id, usuario_id) VALUES (:sessao_id, :usuario_id)";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':sessao_id', $sessao_id);
            $stmt->bindParam(':usuario_id', $solicitacao['solicitante_id']);
            $stmt->execute();
            
            // Marcar notificação como lida
            $sql = "UPDATE notificacoes SET lida = 1 WHERE usuario_id = :usuario_id AND tipo = 'solicitacao_participacao' AND JSON_EXTRACT(dados_extras, '$.sessao_id') = :sessao_id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
            $stmt->bindParam(':sessao_id', $sessao_id);
            $stmt->execute();
            
            $conexao->commit();
            return true;
        } catch (Exception $e) {
            $conexao->rollback();
            return false;
        }
    }
    
    private function rejectRequest($solicitacao_id, $sessao_id) {
        $conexao = $this->sessionModel->getConnection();
        
        try {
            $conexao->beginTransaction();
            
            // Recusar solicitação
            $sql = "DELETE FROM solicitacoes_participacao WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':id', $solicitacao_id);
            $stmt->execute();
            
            // Marcar notificação como lida
            $sql = "UPDATE notificacoes SET lida = 1 WHERE usuario_id = :usuario_id AND tipo = 'solicitacao_participacao' AND JSON_EXTRACT(dados_extras, '$.sessao_id') = :sessao_id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
            $stmt->bindParam(':sessao_id', $sessao_id);
            $stmt->execute();
            
            $conexao->commit();
            return true;
        } catch (Exception $e) {
            $conexao->rollback();
            return false;
        }
    }
    
    private function getPendingRequests($sessao_id) {
        $conexao = $this->sessionModel->getConnection();
        
        $sql = "SELECT s.*, u.username FROM solicitacoes_participacao s 
                JOIN usuarios u ON s.solicitante_id = u.id 
                WHERE s.sessao_id = :sessao_id AND s.status = 'pendente' 
                ORDER BY s.data_solicitacao DESC";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':sessao_id', $sessao_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>