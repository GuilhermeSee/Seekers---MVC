<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center text-white mb-0">Login</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert alert-danger">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/login" method="post" id="loginForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>

                    <div class="text-center mt-3">
                        <p>Não tem uma conta? <a href="<?= BASE_URL ?>/cadastro" class="text-warning">Cadastre-se aqui</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    if (!validarFormulario('loginForm')) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});
</script>