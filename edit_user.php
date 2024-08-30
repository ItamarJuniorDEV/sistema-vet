<?php
session_start();

// Verifica se o usuário está logado e é um administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'administrador') {
    header('Location: login.php');
    exit();
}

require_once 'models/Database.php';
use Models\Database;

$database = new Database();
$conn = $database->getConnection();

// Verifique se o ID do usuário foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar informações do usuário
    $query = "SELECT * FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "Usuário não encontrado.";
        exit();
    }
} else {
    header('Location: manage_users.php');
    exit();
}

// Atualizar informações do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];

    // Atualizar a senha somente se for fornecida
    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
        $query = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, tipo = :tipo WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':senha', $senha);
    } else {
        $query = "UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo WHERE id = :id";
        $stmt = $conn->prepare($query);
    }

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: manage_users.php');
        exit();
    } else {
        $erro = "Erro ao atualizar usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
</head>
<body>
    <h1>Editar Usuário</h1>
    <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
    <form method="post" action="">
        <input type="text" name="nome" value="<?php echo $usuario['nome']; ?>" required>
        <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required>
        <input type="password" name="senha" placeholder="Nova Senha (opcional)">
        <select name="tipo" required>
            <option value="usuario" <?php if ($usuario['tipo'] == 'usuario') echo 'selected'; ?>>Usuário</option>
            <option value="administrador" <?php if ($usuario['tipo'] == 'administrador') echo 'selected'; ?>>Administrador</option>
        </select>
        <button type="submit">Salvar Alterações</button>
    </form>
    <a href="manage_users.php">Voltar</a>
</body>
</html>
