<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'models/Database.php';
use Models\Database;

// Verifique se o arquivo e a classe foram carregados corretamente
echo "Checkpoint 1: Arquivo e namespace carregados.<br>";

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Checkpoint 2: Formulário enviado.<br>";

    $database = new Database(); // Instância da classe Database
    $conn = $database->getConnection();

    if (!$conn) {
        die("Erro na conexão com o banco de dados.");
    }

    echo "Checkpoint 3: Conexão com o banco de dados bem-sucedida.<br>";

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    echo "Checkpoint 4: Variáveis de formulário recebidas.<br>";

    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            echo "Checkpoint 5: Login bem-sucedido.<br>";

            if ($usuario['tipo'] == 'administrador') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: dashboard.php');
            }
            exit();
        } else {
            $erro = 'Senha incorreta. Tente novamente.';
        }
    } else {
        $erro = 'E-mail não encontrado. Tente novamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
    <form method="post" action="">
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
