<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Importar a conexão com o banco de dados
    require_once 'Database.php';
    $database = new Database();
    $conn = $database->getConnection();

    if (!$conn) {
        die("Erro na conexão com o banco de dados.");
    }

    // Obter os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificar se o e-mail já está cadastrado
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $erro = 'E-mail já registrado. Tente outro.';
    } else {
        // Criptografar a senha
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

        // Inserir o novo usuário no banco de dados
        $query = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_hash);

        if ($stmt->execute()) {
            $mensagem = 'Usuário registrado com sucesso!';
        } else {
            $erro = 'Erro ao registrar usuário. Tente novamente.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h2>Registro</h2>
    <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
    <?php if (isset($mensagem)) echo "<p style='color: green;'>$mensagem</p>"; ?>
    <form method="post" action="">
        <input type="text" name="nome" placeholder="Nome Completo" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
