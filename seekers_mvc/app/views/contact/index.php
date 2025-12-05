<?php $titulo = "Contato"; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Fale Conosco</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!$sucesso): ?>
                    <form action="<?= BASE_URL ?>/contato" method="post" id="contatoForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="assunto" class="form-label">Assunto:</label>
                            <input type="text" class="form-control" id="assunto" name="assunto" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Mensagem:</label>
                            <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Informações de Contato</h5>
                </div>
                <div class="card-body">
                    <p><strong>Equipe Seekers:</strong> Entraremos em contato em breve.</p>
                    <p><strong>Horário:</strong> 24/7</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contatoForm')?.addEventListener('submit', function(e) {
    if (!validarFormulario('contatoForm')) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});
</script>