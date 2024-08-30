<?php
session_start();

// Verifica se o usuário está logado e é um administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'administrador') {
    header('Location: login.php');
    exit();
}

require_once 'models/Database.php';
use Models\Database;

// Instância da classe Database
$database = new Database();
$conn = $database->getConnection();

// Lógica para Adicionar, Editar e Excluir Usuários
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Adicionar Novo Usuário
    if (isset($_POST['adicionar'])) {
        // Obter os dados do formulário
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
        $tipo = $_POST['tipo'];

        // Inserir o novo usuário no banco de dados
        $query = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':tipo', $tipo);

        if ($stmt->execute()) {
            $mensagem = "Usuário adicionado com sucesso!";
        } else {
            $erro = "Erro ao adicionar usuário.";
        }
    }

    // Excluir Usuário
    if (isset($_POST['excluir'])) {
        $id = $_POST['id'];

        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $mensagem = "Usuário excluído com sucesso!";
        } else {
            $erro = "Erro ao excluir usuário.";
        }
    }
}

// Buscar todos os usuários
$query = "SELECT * FROM usuarios";
$stmt = $conn->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários</title>
</head>
<body>
    <h1>Gerenciar Usuários</h1>
    <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
    <?php if (isset($mensagem)) echo "<p style='color: green;'>$mensagem</p>"; ?>

    <!-- Formulário para adicionar novo usuário -->
    <h2>Adicionar Novo Usuário</h2>
    <form method="post" action="">
        <input type="text" name="nome" placeholder="Nome Completo" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <select name="tipo" required>
            <option value="usuario">Usuário</option>
            <option value="administrador">Administrador</option>
        </select>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <!-- Lista de usuários -->
    <h2>Lista de Usuários</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?php echo $usuario['id']; ?></td>
                <td><?php echo $usuario['nome']; ?></td>
                <td><?php echo $usuario['email']; ?></td>
                <td><?php echo $usuario['tipo']; ?></td>
                <td>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                        <button type="submit" name="excluir">Excluir</button>
                    </form>
                    <!-- Botão para Editar Usuário -->
                    <form method="get" action="edit_user.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                        <button type="submit" name="editar">Editar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php">Voltar ao Painel do Administrador</a>
</body>
</html>
