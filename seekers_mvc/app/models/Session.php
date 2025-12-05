<?php
class Session extends BaseModel {
    protected $table = 'sessoes_jogo';
    
    public function getOpenSessions($limit = null) {
        $sql = "SELECT s.*, u.username FROM sessoes_jogo s 
                JOIN usuarios u ON s.criador_id = u.id 
                WHERE s.status = 'aberta' AND s.tipo_sessao != 'AI_CHAT' 
                ORDER BY s.criado_em DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $stmt = $this->conexao->prepare($sql);
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByUser($userId) {
        $sql = "SELECT * FROM sessoes_jogo WHERE criador_id = :id ORDER BY criado_em DESC";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getWithCreator($id) {
        $sql = "SELECT s.*, u.username as criador FROM sessoes_jogo s 
                JOIN usuarios u ON s.criador_id = u.id 
                WHERE s.id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getUserSessions($userId) {
        $sql = "SELECT s.*, u.username as criador FROM sessoes_jogo s 
                JOIN usuarios u ON s.criador_id = u.id 
                LEFT JOIN participantes_sessao p ON s.id = p.sessao_id AND p.usuario_id = :id
                WHERE (p.usuario_id = :id2 OR s.criador_id = :id3) AND s.status = 'aberta' AND s.tipo_sessao != 'AI_CHAT' 
                ORDER BY s.criado_em DESC";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':id2', $userId);
        $stmt->bindParam(':id3', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getParticipants($sessionId) {
        $sql = "SELECT u.id as usuario_id, u.username FROM participantes_sessao p 
                JOIN usuarios u ON p.usuario_id = u.id 
                WHERE p.sessao_id = :sessao_id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':sessao_id', $sessionId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function joinSession($sessionId, $userId) {
        $sql = "INSERT INTO participantes_sessao (sessao_id, usuario_id) VALUES (:sessao_id, :usuario_id)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':sessao_id', $sessionId);
        $stmt->bindParam(':usuario_id', $userId);
        return $stmt->execute();
    }
    
    public function leaveSession($sessionId, $userId) {
        $sql = "DELETE FROM participantes_sessao WHERE sessao_id = :sessao_id AND usuario_id = :usuario_id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':sessao_id', $sessionId);
        $stmt->bindParam(':usuario_id', $userId);
        return $stmt->execute();
    }
    
    public function getFavoritesByUser($userId) {
        $sql = "SELECT s.*, u.username as criador FROM sessoes_jogo s 
                JOIN usuarios u ON s.criador_id = u.id 
                JOIN sessoes_favoritas sf ON s.id = sf.sessao_id 
                WHERE sf.usuario_id = :id ORDER BY sf.data_favorito DESC";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUnreadMessages($sessionId, $userId) {
        $sql = "SELECT COUNT(*) as nao_lidas FROM mensagens_sessao m
               LEFT JOIN mensagens_lidas ml ON m.sessao_id = ml.sessao_id AND ml.usuario_id = :usuario_id
               WHERE m.sessao_id = :sessao_id AND m.usuario_id != :usuario_id2
               AND (ml.ultima_leitura IS NULL OR m.data_envio > ml.ultima_leitura)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':sessao_id', $sessionId);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->bindParam(':usuario_id2', $userId);
        $stmt->execute();
        return $stmt->fetch()['nao_lidas'];
    }
}