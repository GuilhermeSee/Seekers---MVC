<?php
class Build extends BaseModel {
    protected $table = 'builds';
    
    public function getAllPublic() {
        $sql = "SELECT b.*, u.username FROM builds b 
                JOIN usuarios u ON b.autor_id = u.id 
                WHERE b.publico = 1 
                ORDER BY b.criado_em DESC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRecent($limit = 3) {
        $sql = "SELECT b.*, u.username FROM builds b 
                JOIN usuarios u ON b.autor_id = u.id 
                WHERE b.publico = 1 
                ORDER BY b.criado_em DESC LIMIT :limit";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByUser($userId) {
        $sql = "SELECT * FROM builds WHERE autor_id = :id ORDER BY criado_em DESC";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getWithAuthor($id) {
        $sql = "SELECT b.*, u.username FROM builds b 
                JOIN usuarios u ON b.autor_id = u.id 
                WHERE b.id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function toggleLike($buildId, $userId) {
        // Verificar se já curtiu
        $sql = "SELECT id FROM curtidas_builds WHERE usuario_id = :usuario_id AND build_id = :build_id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->bindParam(':build_id', $buildId);
        $stmt->execute();
        $liked = $stmt->fetch();
        
        if ($liked) {
            // Remover curtida
            $sql = "DELETE FROM curtidas_builds WHERE usuario_id = :usuario_id AND build_id = :build_id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':usuario_id', $userId);
            $stmt->bindParam(':build_id', $buildId);
            $stmt->execute();
            
            // Decrementar contador
            $sql = "UPDATE builds SET curtidas = curtidas - 1 WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $buildId);
            $stmt->execute();
            
            return false;
        } else {
            // Adicionar curtida
            $sql = "INSERT INTO curtidas_builds (usuario_id, build_id) VALUES (:usuario_id, :build_id)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':usuario_id', $userId);
            $stmt->bindParam(':build_id', $buildId);
            $stmt->execute();
            
            // Incrementar contador
            $sql = "UPDATE builds SET curtidas = curtidas + 1 WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $buildId);
            $stmt->execute();
            
            return true;
        }
    }
    
    public function getLikesCount($buildId) {
        $sql = "SELECT curtidas FROM builds WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $buildId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['curtidas'] ?? 0;
    }
    
    public function hasUserLiked($buildId, $userId) {
        $sql = "SELECT id FROM curtidas_builds WHERE usuario_id = :usuario_id AND build_id = :build_id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':usuario_id', $userId);
        $stmt->bindParam(':build_id', $buildId);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
    
    public function getFavoritesByUser($userId) {
        $sql = "SELECT b.*, u.username as autor FROM builds b 
                JOIN usuarios u ON b.autor_id = u.id 
                JOIN builds_favoritas bf ON b.id = bf.build_id 
                WHERE bf.usuario_id = :id ORDER BY bf.data_favorito DESC";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}