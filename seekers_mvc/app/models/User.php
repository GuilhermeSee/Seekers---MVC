<?php
class User extends BaseModel {
    protected $table = 'usuarios';
    
    public function findByUsername($username) {
        $sql = "SELECT * FROM usuarios WHERE username = :username";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function register($data) {
        $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        $data['plataformas'] = '[]';
        $data['jogos_preferidos'] = '[]';
        $data['usa_mods'] = 0;
        $data['bio'] = '';
        
        return $this->create($data);
    }
    
    public function updateLastAccess($id) {
        $sql = "UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    public function getBuildsCount($userId) {
        $sql = "SELECT COUNT(*) as total FROM builds WHERE autor_id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    
    public function getSessionsCount($userId) {
        $sql = "SELECT COUNT(*) as total FROM sessoes_jogo WHERE criador_id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    
    public function getTotalLikes($userId) {
        $sql = "SELECT SUM(curtidas) as total FROM builds WHERE autor_id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    public function getNotifications($userId) {
        $sql = "SELECT * FROM notificacoes WHERE usuario_id = :usuario_id ORDER BY data_criacao DESC";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function markAllNotificationsAsRead($userId) {
        $sql = "UPDATE notificacoes SET lida = 1 WHERE usuario_id = :usuario_id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':usuario_id', $userId);
        return $stmt->execute();
    }
}