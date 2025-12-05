<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../../config/env.php';

class ChatController extends BaseController {
    
    public function ia() {
        $this->requireAuth();
        
        // Buscar ou criar sessão de IA
        $conexao = conexao();
        $sql = "SELECT id FROM sessoes_jogo WHERE tipo_sessao = 'AI_CHAT' LIMIT 1";
        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $sessao_ai = $stmt->fetch(PDO::FETCH_ASSOC);
        $sessao_id = $sessao_ai['id'];
        
        // Buscar mensagens do usuário com a IA
        $sql = "SELECT m.*, 
                CASE 
                    WHEN m.tipo = 'ai' THEN 'Seekers AI' 
                    WHEN u.username IS NOT NULL THEN u.username 
                    ELSE 'Sistema' 
                END as username
                FROM mensagens_sessao m 
                LEFT JOIN usuarios u ON m.usuario_id = u.id 
                WHERE m.sessao_id = :sessao_id 
                AND (m.usuario_id = :usuario_id OR m.tipo = 'ai')
                ORDER BY m.data_envio ASC";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':sessao_id', $sessao_id);
        $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
        $stmt->execute();
        $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Filtrar mensagens para mostrar apenas pares pergunta-resposta
        $mensagens_filtradas = [];
        for($i = 0; $i < count($mensagens); $i++) {
            $msg = $mensagens[$i];
            
            if($msg['usuario_id'] == $_SESSION['usuarioLogado']) {
                $mensagens_filtradas[] = $msg;
                
                if(isset($mensagens[$i + 1]) && $mensagens[$i + 1]['tipo'] == 'ai') {
                    $mensagens_filtradas[] = $mensagens[$i + 1];
                    $i++;
                }
            }
        }
        
        $this->view('chat/ia', [
            'titulo' => 'Chat com IA',
            'mensagens' => $mensagens_filtradas,
            'sessao_id' => $sessao_id
        ]);
    }
}
?>