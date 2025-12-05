<?php
require_once 'BaseController.php';

class SessionController extends BaseController {
    private $sessionModel;
    
    public function __construct() {
        $this->sessionModel = new Session();
    }
    
    public function index() {
        $sessoes = $this->sessionModel->getOpenSessions();
        
        $this->view('sessions/index', [
            'titulo' => 'Sessões Abertas',
            'sessoes' => $sessoes
        ]);
    }
    
    public function details() {
        $id = $_GET['id'] ?? 0;
        $sessao = $this->sessionModel->getWithCreator($id);
        
        if (!$sessao) {
            $this->redirect('/sessoes');
            return;
        }
        
        $participantes = $this->sessionModel->getParticipants($id);
        
        $this->view('sessions/details', [
            'titulo' => $sessao['jogo'],
            'sessao' => $sessao,
            'participantes' => $participantes
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $this->view('sessions/create', [
            'titulo' => 'Criar Sessão',
            'mensagem' => '',
            'sucesso' => false
        ]);
    }
    
    public function store() {
        $this->requireAuth();
        
        $jogo = $_POST['jogo'] ?? '';
        $plataforma = $_POST['plataforma'] ?? '';
        $tipo_sessao = $_POST['tipo_sessao'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $usa_mods = isset($_POST['usa_mods']) ? 1 : 0;
        $max_participantes = $_POST['max_participantes'] ?? 4;
        
        if (empty($jogo) || empty($plataforma) || empty($tipo_sessao)) {
            $this->view('sessions/create', [
                'titulo' => 'Criar Sessão',
                'mensagem' => 'Campos obrigatórios não preenchidos',
                'sucesso' => false
            ]);
            return;
        }
        
        $sessionData = [
            'jogo' => $jogo,
            'plataforma' => $plataforma,
            'tipo_sessao' => $tipo_sessao,
            'usa_mods' => $usa_mods,
            'descricao' => $descricao,
            'criador_id' => $_SESSION['usuarioLogado'],
            'status' => 'aberta',
            'max_participantes' => $max_participantes
        ];
        
        if ($this->sessionModel->create($sessionData)) {
            $this->view('sessions/create', [
                'titulo' => 'Criar Sessão',
                'mensagem' => 'Sessão criada com sucesso!',
                'sucesso' => true
            ]);
        } else {
            $this->view('sessions/create', [
                'titulo' => 'Criar Sessão',
                'mensagem' => 'Erro ao criar sessão',
                'sucesso' => false
            ]);
        }
    }
    
    public function edit() {
        $this->requireAuth();
        
        $id = $_GET['id'] ?? 0;
        $sessao = $this->sessionModel->find($id);
        
        if (!$sessao || $sessao['criador_id'] != $_SESSION['usuarioLogado']) {
            $this->redirect('/dashboard');
            return;
        }
        
        // Processar atualização se for POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update();
            return;
        }
        
        // Buscar participantes
        $participantes = $this->sessionModel->getParticipants($id);
        
        $this->view('sessions/edit', [
            'titulo' => 'Editar Sessão',
            'sessao' => $sessao,
            'participantes' => $participantes,
            'mensagem' => '',
            'sucesso' => false
        ]);
    }
    
    public function update() {
        $this->requireAuth();
        
        $id = $_POST['id'] ?? 0;
        $sessao = $this->sessionModel->find($id);
        
        if (!$sessao || $sessao['criador_id'] != $_SESSION['usuarioLogado']) {
            $this->redirect('/dashboard');
            return;
        }
        
        $jogo = $_POST['jogo'] ?? '';
        $plataforma = $_POST['plataforma'] ?? '';
        $tipo_sessao = $_POST['tipo_sessao'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $usa_mods = isset($_POST['usa_mods']) ? 1 : 0;
        $max_participantes = $_POST['max_participantes'] ?? 4;
        
        $participantes = $this->sessionModel->getParticipants($id);
        
        if (empty($jogo) || empty($plataforma) || empty($tipo_sessao) || empty($descricao)) {
            $this->view('sessions/edit', [
                'titulo' => 'Editar Sessão',
                'sessao' => $sessao,
                'participantes' => $participantes,
                'mensagem' => 'Campos obrigatórios não preenchidos',
                'sucesso' => false
            ]);
            return;
        }
        
        $sessionData = [
            'jogo' => $jogo,
            'plataforma' => $plataforma,
            'tipo_sessao' => $tipo_sessao,
            'usa_mods' => $usa_mods,
            'descricao' => $descricao,
            'max_participantes' => $max_participantes
        ];
        
        if ($this->sessionModel->update($id, $sessionData)) {
            $sessaoAtualizada = array_merge($sessao, $sessionData);
            $this->view('sessions/edit', [
                'titulo' => 'Editar Sessão',
                'sessao' => $sessaoAtualizada,
                'participantes' => $participantes,
                'mensagem' => 'Sessão atualizada com sucesso!',
                'sucesso' => true
            ]);
        } else {
            $this->view('sessions/edit', [
                'titulo' => 'Editar Sessão',
                'sessao' => $sessao,
                'participantes' => $participantes,
                'mensagem' => 'Erro ao atualizar sessão',
                'sucesso' => false
            ]);
        }
    }
    
    public function chat() {
        $this->requireAuth();
        
        $id = $_GET['id'] ?? 0;
        $sessao = $this->sessionModel->getWithCreator($id);
        
        if (!$sessao) {
            $this->redirect('/sessoes');
            return;
        }
        
        // Verificar se o usuário pode acessar o chat
        $conexao = $this->sessionModel->getConnection();
        $sql = "SELECT s.id FROM sessoes_jogo s 
                LEFT JOIN participantes_sessao p ON s.id = p.sessao_id AND p.usuario_id = :usuario_id
                WHERE s.id = :sessao_id AND (s.criador_id = :usuario_id2 OR p.usuario_id IS NOT NULL)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':sessao_id', $id);
        $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
        $stmt->bindParam(':usuario_id2', $_SESSION['usuarioLogado']);
        $stmt->execute();
        
        if (!$stmt->fetch()) {
            $this->redirect('/dashboard');
            return;
        }
        
        // Marcar mensagens como lidas
        $sql = "INSERT INTO mensagens_lidas (usuario_id, sessao_id, ultima_leitura) 
                VALUES (:usuario_id, :sessao_id, NOW()) 
                ON DUPLICATE KEY UPDATE ultima_leitura = NOW()";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
        $stmt->bindParam(':sessao_id', $id);
        $stmt->execute();
        
        // Buscar mensagens
        $sql = "SELECT m.*, u.username FROM mensagens_sessao m 
                LEFT JOIN usuarios u ON m.usuario_id = u.id 
                WHERE m.sessao_id = :sessao_id 
                ORDER BY m.data_envio ASC";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':sessao_id', $id);
        $stmt->execute();
        $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('sessions/chat', [
            'titulo' => 'Chat - ' . $sessao['jogo'],
            'sessao' => $sessao,
            'mensagens' => $mensagens
        ]);
    }
}