<?php
session_start();

// Inclua o arquivo de conexão com o banco de dados
require_once 'models/Database.php';
use Models\Database;

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verificar'])) {
    $email = $_POST['email'];
    $codigo = $_POST['codigo'];

    // Verifique o código e a validade
    $query = "SELECT * FROM usuarios WHERE email = :email AND codigo_verificacao = :codigo AND validade_codigo >= NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Código válido, permita redefinição de senha
        header("Location: reset_password.php?email=" . urlencode($email));
        exit();
    } else {
        $erro = "Código inválido ou expirado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Verificar Código</title>
</head>
<body>
    <h1>Verificar Código de Verificação</h1>
    <form method="post" action="">
        <input type="email" name="email" placeholder="Digite seu e-mail" required>
        <input type="text" name="codigo" placeholder="Digite o código de verificação" required>
        <button type="submit" name="verificar">Verificar Código</button>
    </form>
    <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
</body>
</html>
