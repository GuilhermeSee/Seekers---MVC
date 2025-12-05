<?php
require_once 'BaseController.php';

class HomeController extends BaseController {
    public function index() {
        $buildModel = new Build();
        $sessionModel = new Session();
        
        // Buscar builds recentes
        $builds_recentes = $buildModel->getRecent(3);
        
        // Buscar sessões abertas
        $sessoes_abertas = $sessionModel->getOpenSessions(3);
        
        $this->view('home/index', [
            'titulo' => 'Início',
            'builds_recentes' => $builds_recentes,
            'sessoes_abertas' => $sessoes_abertas
        ]);
    }
}