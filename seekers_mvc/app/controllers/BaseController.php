<?php
class BaseController {
    protected function view($viewName, $data = []) {
        extract($data);
        
        ob_start();
        require_once "app/views/{$viewName}.php";
        $content = ob_get_clean();
        
        require_once "app/views/layouts/main.php";
    }
    
    protected function redirect($path) {
        header("Location: " . BASE_URL . $path);
        exit;
    }
    
    protected function requireAuth() {
        if (!isset($_SESSION['usuarioLogado'])) {
            $this->redirect('/login');
        }
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}