<?php
session_start();

// Inclua o arquivo de conexão com o banco de dados
require_once 'models/Database.php';
use Models\Database;

// Inclua o PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['recuperar'])) {
    $email = $_POST['email'];

    // Verifique se o e-mail existe no banco de dados
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Gerar um código de verificação aleatório
        $codigo = rand(100000, 999999);
        $validade = date('Y-m-d H:i:s', strtotime('+1 hour')); // Validade de 1 hora

        // Armazene o código e a validade no banco de dados
        $query = "UPDATE usuarios SET codigo_verificacao = :codigo, validade_codigo = :validade WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':validade', $validade);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Enviar e-mail com o código de verificação
        $mail = new PHPMailer(true); // Instancia PHPMailer
        try {
            // Configuração do servidor SMTP
            $mail->isSMTP(); // Defina o uso do SMTP
            $mail->Host = 'smtp.gmail.com'; // Endereço do servidor SMTP
            $mail->SMTPAuth = true; // Habilite a autenticação SMTP
            $mail->Username = 'cdajuniorf@gmail.com'; // Seu endereço de e-mail do Gmail
            $mail->Password = 'lkhp khoq etdb uht'; // Sua senha de aplicativo
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilite criptografia TLS
            $mail->Port = 587; // Porta TCP para TLS

            // Remetente e destinatário
            $mail->setFrom('cdajuniorf@gmail.com', 'Sistema Vet');
            $mail->addAddress($email);

            // Conteúdo do e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha';
            $mail->Body    = "Seu código de verificação é: <strong>$codigo</strong>";

            $mail->send();
            $mensagem = "Um e-mail de verificação foi enviado para $email.";
        } catch (Exception $e) {
            $mensagem = "Erro ao enviar o e-mail de verificação. Detalhes: {$mail->ErrorInfo}";
        }
    } else {
        $mensagem = "E-mail não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
</head>
<body>
    <h1>Recuperar Senha</h1>
    <form method="post" action="">
        <label for="email">Digite seu e-mail:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit" name="recuperar">Recuperar Senha</button>
    </form>
    <?php if (isset($mensagem)) echo "<p>$mensagem</p>"; ?>
</body>
</html>
