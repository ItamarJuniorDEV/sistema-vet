<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'models/Database.php';
use Models\Database;

$database = new Database();
$conn = $database->getConnection();

// Carrega os dados do usuário logado
$id = $_SESSION['usuario_id'];

$query = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit();
}

// Atualiza as informações do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    // Atualiza a senha somente se for fornecida
    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
        $query = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':senha', $senha);
    } else {
        $query = "UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id";
        $stmt = $conn->prepare($query);
    }

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $mensagem = "Informações atualizadas com sucesso!";
        // Atualiza os dados na sessão
        $_SESSION['usuario_nome'] = $nome;
    } else {
        $erro = "Erro ao atualizar informações.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
</head>
<body>
    <h1>Perfil de <?php echo htmlspecialchars($usuario['nome']); ?></h1>
    <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
    <?php if (isset($mensagem)) echo "<p style='color: green;'>$mensagem</p>"; ?>
    
    <!-- Formulário para editar o perfil -->
    <form method="post" action="">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        
        <label for="senha">Nova Senha (deixe em branco para não alterar):</label>
        <input type="password" id="senha" name="senha">
        
        <button type="submit">Salvar Alterações</button>
    </form>

    <a href="dashboard.php">Voltar ao Painel</a>
</body>
</html>
