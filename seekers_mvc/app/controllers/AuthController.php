<?php
require_once 'BaseController.php';

class AuthController extends BaseController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        $this->view('auth/login', [
            'titulo' => 'Login',
            'mensagem' => ''
        ]);
    }
    
    public function processLogin() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $this->view('auth/login', [
                'titulo' => 'Login',
                'mensagem' => 'Todos os campos são obrigatórios'
            ]);
            return;
        }
        
        $user = $this->userModel->findByUsername($username);
        
        if ($user && password_verify($password, $user['senha'])) {
            $_SESSION['usuarioLogado'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            $this->userModel->updateLastAccess($user['id']);
            $this->redirect('/dashboard');
        } else {
            $this->view('auth/login', [
                'titulo' => 'Login',
                'mensagem' => 'Username ou senha incorretos'
            ]);
        }
    }
    
    public function register() {
        $this->view('auth/register', [
            'titulo' => 'Cadastro',
            'mensagem' => '',
            'sucesso' => false
        ]);
    }
    
    public function processRegister() {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validações
        if (empty($username) || empty($email) || empty($password)) {
            $this->view('auth/register', [
                'titulo' => 'Cadastro',
                'mensagem' => 'Todos os campos são obrigatórios',
                'sucesso' => false
            ]);
            return;
        }
        
        if ($password !== $confirmPassword) {
            $this->view('auth/register', [
                'titulo' => 'Cadastro',
                'mensagem' => 'As senhas não coincidem',
                'sucesso' => false
            ]);
            return;
        }
        
        if (strlen($password) < 6) {
            $this->view('auth/register', [
                'titulo' => 'Cadastro',
                'mensagem' => 'A senha deve ter pelo menos 6 caracteres',
                'sucesso' => false
            ]);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('auth/register', [
                'titulo' => 'Cadastro',
                'mensagem' => 'Email inválido',
                'sucesso' => false
            ]);
            return;
        }
        
        // Verificar se username já existe
        if ($this->userModel->findByUsername($username)) {
            $this->view('auth/register', [
                'titulo' => 'Cadastro',
                'mensagem' => 'Username já existe',
                'sucesso' => false
            ]);
            return;
        }
        
        // Criar usuário
        $userData = [
            'username' => $username,
            'email' => $email,
            'senha' => $password
        ];
        
        if ($this->userModel->register($userData)) {
            $this->view('auth/register', [
                'titulo' => 'Cadastro',
                'mensagem' => 'Cadastro realizado com sucesso! Faça login.',
                'sucesso' => true
            ]);
        } else {
            $this->view('auth/register', [
                'titulo' => 'Cadastro',
                'mensagem' => 'Erro ao cadastrar usuário',
                'sucesso' => false
            ]);
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}