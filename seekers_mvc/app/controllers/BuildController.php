<?php
require_once 'BaseController.php';

class BuildController extends BaseController {
    private $buildModel;
    
    public function __construct() {
        $this->buildModel = new Build();
    }
    
    public function index() {
        $builds = $this->buildModel->getAllPublic();
        
        $this->view('builds/index', [
            'titulo' => 'Builds da Comunidade',
            'builds' => $builds
        ]);
    }
    
    public function details() {
        $id = $_GET['id'] ?? 0;
        $build = $this->buildModel->getWithAuthor($id);
        
        if (!$build) {
            $this->redirect('/builds');
            return;
        }
        
        $this->view('builds/details', [
            'titulo' => $build['nome'],
            'build' => $build
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $this->view('builds/create', [
            'titulo' => 'Criar Build',
            'mensagem' => '',
            'sucesso' => false
        ]);
    }
    
    public function store() {
        $this->requireAuth();
        
        $nome = $_POST['nome'] ?? '';
        $jogo = $_POST['jogo'] ?? '';
        $classe = $_POST['classe'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        
        // Atributos
        $atributos = [
            'vigor' => $_POST['vigor'] ?? 10,
            'forca' => $_POST['forca'] ?? 10,
            'destreza' => $_POST['destreza'] ?? 10,
            'inteligencia' => $_POST['inteligencia'] ?? 10,
            'fe' => $_POST['fe'] ?? 10
        ];
        
        // Calcular nível
        $nivel_calculado = 0;
        foreach($atributos as $valor) {
            if($valor > 10) {
                $nivel_calculado += ($valor - 10);
            } else {
                $nivel_calculado -= (10 - $valor);
            }
        }
        $nivel = max(1, $nivel_calculado + 50);
        
        // Equipamentos
        $equipamentos = [
            'arma_principal' => $_POST['arma_principal'] ?? '',
            'arma_secundaria' => $_POST['arma_secundaria'] ?? '',
            'armadura' => $_POST['armadura'] ?? '',
            'anel1' => $_POST['anel1'] ?? '',
            'anel2' => $_POST['anel2'] ?? ''
        ];
        
        if (empty($nome) || empty($jogo) || empty($classe)) {
            $this->view('builds/create', [
                'titulo' => 'Criar Build',
                'mensagem' => 'Campos obrigatórios não preenchidos',
                'sucesso' => false
            ]);
            return;
        }
        
        $buildData = [
            'nome' => $nome,
            'jogo' => $jogo,
            'classe' => $classe,
            'nivel' => $nivel,
            'atributos' => json_encode($atributos),
            'equipamentos' => json_encode($equipamentos),
            'descricao' => $descricao,
            'autor_id' => $_SESSION['usuarioLogado'],
            'publico' => 1,
            'curtidas' => 0
        ];
        
        if ($this->buildModel->create($buildData)) {
            $this->view('builds/create', [
                'titulo' => 'Criar Build',
                'mensagem' => 'Build criada com sucesso!',
                'sucesso' => true
            ]);
        } else {
            $this->view('builds/create', [
                'titulo' => 'Criar Build',
                'mensagem' => 'Erro ao criar build',
                'sucesso' => false
            ]);
        }
    }
    
    public function edit() {
        $this->requireAuth();
        
        $id = $_GET['id'] ?? 0;
        $build = $this->buildModel->find($id);
        
        if (!$build || $build['autor_id'] != $_SESSION['usuarioLogado']) {
            $this->redirect('/dashboard');
            return;
        }
        
        $this->view('builds/edit', [
            'titulo' => 'Editar Build',
            'build' => $build,
            'mensagem' => '',
            'sucesso' => false
        ]);
    }
    
    public function update() {
        $this->requireAuth();
        
        $id = $_POST['id'] ?? 0;
        $build = $this->buildModel->find($id);
        
        if (!$build || $build['autor_id'] != $_SESSION['usuarioLogado']) {
            $this->redirect('/dashboard');
            return;
        }
        
        $nome = $_POST['nome'] ?? '';
        $jogo = $_POST['jogo'] ?? '';
        $classe = $_POST['classe'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        
        // Atributos
        $atributos = [
            'vigor' => $_POST['vigor'] ?? 10,
            'forca' => $_POST['forca'] ?? 10,
            'destreza' => $_POST['destreza'] ?? 10,
            'inteligencia' => $_POST['inteligencia'] ?? 10,
            'fe' => $_POST['fe'] ?? 10
        ];
        
        // Calcular nível
        $nivel_calculado = 0;
        foreach($atributos as $valor) {
            if($valor > 10) {
                $nivel_calculado += ($valor - 10);
            } else {
                $nivel_calculado -= (10 - $valor);
            }
        }
        $nivel = max(1, $nivel_calculado + 50);
        
        // Equipamentos
        $equipamentos = [
            'arma_principal' => $_POST['arma_principal'] ?? '',
            'arma_secundaria' => $_POST['arma_secundaria'] ?? '',
            'armadura' => $_POST['armadura'] ?? '',
            'anel1' => $_POST['anel1'] ?? '',
            'anel2' => $_POST['anel2'] ?? ''
        ];
        
        if (empty($nome) || empty($jogo) || empty($classe)) {
            $this->view('builds/edit', [
                'titulo' => 'Editar Build',
                'build' => $build,
                'mensagem' => 'Campos obrigatórios não preenchidos',
                'sucesso' => false
            ]);
            return;
        }
        
        $buildData = [
            'nome' => $nome,
            'jogo' => $jogo,
            'classe' => $classe,
            'nivel' => $nivel,
            'atributos' => json_encode($atributos),
            'equipamentos' => json_encode($equipamentos),
            'descricao' => $descricao
        ];
        
        if ($this->buildModel->update($id, $buildData)) {
            $this->view('builds/edit', [
                'titulo' => 'Editar Build',
                'build' => array_merge($build, $buildData),
                'mensagem' => 'Build atualizada com sucesso!',
                'sucesso' => true
            ]);
        } else {
            $this->view('builds/edit', [
                'titulo' => 'Editar Build',
                'build' => $build,
                'mensagem' => 'Erro ao atualizar build',
                'sucesso' => false
            ]);
        }
    }
}