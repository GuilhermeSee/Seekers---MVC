<?php
require_once 'BaseController.php';

class UserController extends BaseController {
    private $userModel;
    private $buildModel;
    private $sessionModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->buildModel = new Build();
        $this->sessionModel = new Session();
    }
    
    public function dashboard() {
        $this->requireAuth();
        
        $userId = $_SESSION['usuarioLogado'];
        
        // Buscar dados do usuário
        $usuario = $this->userModel->find($userId);
        
        // Buscar builds do usuário
        $minhas_builds = $this->buildModel->getByUser($userId);
        
        // Buscar sessões do usuário
        $minhas_sessoes = $this->sessionModel->getByUser($userId);
        
        // Buscar sessões que o usuário participa
        $sessoes_participando = $this->sessionModel->getUserSessions($userId);
        
        // Buscar favoritos
        $builds_favoritas = $this->buildModel->getFavoritesByUser($userId);
        $sessoes_favoritas = $this->sessionModel->getFavoritesByUser($userId);
        
        // Adicionar informações de mensagens não lidas para cada sessão
        foreach ($sessoes_participando as &$sessao) {
            $participantes = $this->sessionModel->getParticipants($sessao['id']);
            array_unshift($participantes, ['username' => $sessao['criador']]);
            $sessao['participantes'] = $participantes;
            $sessao['mensagens_nao_lidas'] = $this->sessionModel->getUnreadMessages($sessao['id'], $userId);
        }
        
        $this->view('user/dashboard', [
            'titulo' => 'Dashboard',
            'usuario' => $usuario,
            'minhas_builds' => $minhas_builds,
            'minhas_sessoes' => $minhas_sessoes,
            'sessoes_participando' => $sessoes_participando,
            'builds_favoritas' => $builds_favoritas,
            'sessoes_favoritas' => $sessoes_favoritas
        ]);
    }
    
    public function profile() {
        $this->requireAuth();
        
        $userId = $_SESSION['usuarioLogado'];
        $mensagem = "";
        $sucesso = false;
        
        // Atualizar perfil
        if(isset($_POST["username"])){
            $username = $_POST["username"];
            $email = $_POST["email"];
            $bio = $_POST["bio"];
            $plataformas = isset($_POST["plataformas"]) ? $_POST["plataformas"] : [];
            $jogos_preferidos = isset($_POST["jogos_preferidos"]) ? $_POST["jogos_preferidos"] : [];
            $usa_mods = isset($_POST["usa_mods"]) ? 1 : 0;

            if(empty($username) || empty($email)){
                $mensagem = "Username e email são obrigatórios";
            } else {
                // Verificar se username já existe (exceto o próprio usuário)
                $existingUser = $this->userModel->findByUsername($username);
                if($existingUser && $existingUser['id'] != $userId){
                    $mensagem = "Username já existe";
                } else {
                    $userData = [
                        'username' => $username,
                        'email' => $email,
                        'bio' => $bio,
                        'plataformas' => json_encode($plataformas),
                        'jogos_preferidos' => json_encode($jogos_preferidos),
                        'usa_mods' => $usa_mods
                    ];
                    
                    if($this->userModel->update($userId, $userData)){
                        $sucesso = true;
                        $_SESSION['username'] = $username;
                        $this->redirect('/dashboard');
                        return;
                    } else {
                        $mensagem = "Erro ao atualizar perfil";
                    }
                }
            }
        }
        
        $usuario = $this->userModel->find($userId);
        $plataformas_decode = json_decode($usuario['plataformas'], true);
        $plataformas_usuario = $plataformas_decode ?: [];
        $jogos_decode = json_decode($usuario['jogos_preferidos'], true);
        $jogos_usuario = $jogos_decode ?: [];
        
        $this->view('user/profile', [
            'titulo' => 'Editar Perfil',
            'usuario' => $usuario,
            'plataformas_usuario' => $plataformas_usuario,
            'jogos_usuario' => $jogos_usuario,
            'mensagem' => $mensagem,
            'sucesso' => $sucesso
        ]);
    }
    
    public function favorites() {
        $this->requireAuth();
        
        $userId = $_SESSION['usuarioLogado'];
        $builds_favoritas = $this->buildModel->getFavoritesByUser($userId);
        $sessoes_favoritas = $this->sessionModel->getFavoritesByUser($userId);
        
        $this->view('user/favorites', [
            'titulo' => 'Meus Favoritos',
            'builds_favoritas' => $builds_favoritas,
            'sessoes_favoritas' => $sessoes_favoritas
        ]);
    }
    
    public function notifications() {
        $this->requireAuth();
        
        $userId = $_SESSION['usuarioLogado'];
        
        // Marcar todas como lidas
        if(isset($_GET['marcar_lidas'])){
            $this->userModel->markAllNotificationsAsRead($userId);
            $this->redirect('/notificacoes');
            return;
        }
        
        // Buscar notificações
        $notificacoes = $this->userModel->getNotifications($userId);
        
        $this->view('user/notifications', [
            'titulo' => 'Notificações',
            'notificacoes' => $notificacoes
        ]);
    }
}