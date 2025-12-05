<?php
// Sistema de roteamento simples
class Router {
    private $routes = [];
    
    public function get($path, $controller, $action) {
        $this->routes['GET'][$path] = ['controller' => $controller, 'action' => $action];
    }
    
    public function post($path, $controller, $action) {
        $this->routes['POST'][$path] = ['controller' => $controller, 'action' => $action];
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace('/seekers_mvc', '', $path);
        
        if ($path === '' || $path === '/') {
            $path = '/';
        }
        
        // Verificar se é arquivo estático
        if (strpos($path, '/public/') === 0) {
            return;
        }
        
        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];
            $controllerName = $route['controller'];
            $actionName = $route['action'];
            
            $controller = new $controllerName();
            $controller->$actionName();
        } else {
            // Rota não encontrada
            http_response_code(404);
            echo "Página não encontrada";
        }
    }
}

// Definir rotas
$router = new Router();

// Rotas principais
$router->get('/', 'HomeController', 'index');
$router->get('/login', 'AuthController', 'login');
$router->post('/login', 'AuthController', 'processLogin');
$router->get('/cadastro', 'AuthController', 'register');
$router->post('/cadastro', 'AuthController', 'processRegister');
$router->get('/logout', 'AuthController', 'logout');

// Rotas de builds
$router->get('/builds', 'BuildController', 'index');
$router->get('/build_detalhes', 'BuildController', 'details');
$router->get('/criar_build', 'BuildController', 'create');
$router->post('/criar_build', 'BuildController', 'store');
$router->get('/editar_build', 'BuildController', 'edit');
$router->post('/editar_build', 'BuildController', 'update');

// Rotas de sessões
$router->get('/sessoes', 'SessionController', 'index');
$router->get('/sessao_detalhes', 'SessionController', 'details');
$router->get('/criar_sessao', 'SessionController', 'create');
$router->post('/criar_sessao', 'SessionController', 'store');
$router->get('/editar_sessao', 'SessionController', 'edit');
$router->post('/editar_sessao', 'SessionController', 'update');
$router->get('/chat_sessao', 'SessionController', 'chat');

// Rotas de usuário
$router->get('/dashboard', 'UserController', 'dashboard');
$router->get('/perfil', 'UserController', 'profile');
$router->post('/perfil', 'UserController', 'profile');
$router->get('/favoritos', 'UserController', 'favorites');
$router->get('/notificacoes', 'UserController', 'notifications');

// Rotas especiais
$router->get('/chat_ia', 'ChatController', 'ia');
$router->get('/contato', 'ContactController', 'index');
$router->post('/contato', 'ContactController', 'send');

// Rotas de solicitações
$router->get('/gerenciar_solicitacoes', 'RequestController', 'manage');
$router->post('/gerenciar_solicitacoes', 'RequestController', 'manage');

return $router;