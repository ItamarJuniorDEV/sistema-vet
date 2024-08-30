<?php
session_start();

// Inclua o arquivo de conexão com o banco de dados
require_once 'models/Database.php';
use Models\Database;

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['redefinir'])) {
    $email = $_GET['email'];
    $novaSenha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

    // Atualize a senha no banco de dados
    $query = "UPDATE usuarios SET senha = :novaSenha, codigo_verificacao = NULL, validade_codigo = NULL WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':novaSenha', $novaSenha);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $mensagem = "Senha redefinida com sucesso. Você já pode fazer login com sua nova senha.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
</head>
<body>
    <h1>Redefinir Senha</h1>
    <form method="post" action="">
        <input type="password" name="nova_senha" placeholder="Digite sua nova senha" required>
        <button type="submit" name="redefinir">Redefinir Senha</button>
    </form>
    <?php if (isset($mensagem)) echo "<p style='color: green;'>$mensagem</p>"; ?>
</body>
</html>
