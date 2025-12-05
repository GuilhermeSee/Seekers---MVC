<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../../config/env.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ContactController extends BaseController {
    
    private function enviarEmail($nome, $email, $assunto, $mensagem) {
        // Verificar se PHPMailer existe
        $phpmailerPath = __DIR__ . '/../../lib/PHPMailer/PHPMailer.php';
        if (!file_exists($phpmailerPath)) {
            return false;
        }
        
        require_once __DIR__ . '/../../lib/PHPMailer/PHPMailer.php';
        require_once __DIR__ . '/../../lib/PHPMailer/SMTP.php';
        require_once __DIR__ . '/../../lib/PHPMailer/Exception.php';
        
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USER'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
            
            $mail->setFrom($_ENV['MAIL_USER'], 'Seekers Platform');
            $mail->addAddress($_ENV['MAIL_USER']);
            $mail->addReplyTo($email, $nome);
            
            $mail->Subject = "[SEEKERS] Novo Contato: " . $assunto;
            $mail->Body = "Nome: $nome\n";
            $mail->Body .= "Email: $email\n";
            $mail->Body .= "Assunto: $assunto\n\n";
            $mail->Body .= "Mensagem:\n$mensagem\n\n";
            $mail->Body .= "---\nEnviado pelo sistema Seekers";
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro ao enviar email: " . $mail->ErrorInfo);
            return false;
        }
    }
    
    public function index() {
        $this->view('contact/index', [
            'titulo' => 'Contato',
            'mensagem' => '',
            'sucesso' => false
        ]);
    }
    
    public function send() {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $assunto = $_POST['assunto'] ?? '';
        $mensagem_contato = $_POST['mensagem'] ?? '';
        
        if (empty($nome) || empty($email) || empty($assunto) || empty($mensagem_contato)) {
            $this->view('contact/index', [
                'titulo' => 'Contato',
                'mensagem' => 'Todos os campos são obrigatórios',
                'sucesso' => false
            ]);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('contact/index', [
                'titulo' => 'Contato',
                'mensagem' => 'Email inválido',
                'sucesso' => false
            ]);
            return;
        }
        
        try {
            // Salvar no banco de dados
            $conexao = conexao();
            $sql = "INSERT INTO contatos (nome, email, assunto, mensagem) VALUES (:nome, :email, :assunto, :mensagem)";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':assunto', $assunto);
            $stmt->bindParam(':mensagem', $mensagem_contato);
            
            if ($stmt->execute()) {
                // Tentar enviar email
                $email_enviado = $this->enviarEmail($nome, $email, $assunto, $mensagem_contato);
                
                if ($email_enviado) {
                    $mensagem = "Mensagem enviada com sucesso! Recebemos seu contato e responderemos em breve.";
                } else {
                    $mensagem = "Mensagem salva com sucesso! Houve um problema no envio do email, mas entraremos em contato.";
                }
                
                $this->view('contact/index', [
                    'titulo' => 'Contato',
                    'mensagem' => $mensagem,
                    'sucesso' => true
                ]);
            } else {
                $this->view('contact/index', [
                    'titulo' => 'Contato',
                    'mensagem' => 'Erro ao salvar mensagem. Tente novamente.',
                    'sucesso' => false
                ]);
            }
        } catch (Exception $e) {
            $this->view('contact/index', [
                'titulo' => 'Contato',
                'mensagem' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.',
                'sucesso' => true
            ]);
        }
    }
}
?>