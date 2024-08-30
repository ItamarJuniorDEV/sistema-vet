<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'administrador') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Bem-vindo ao Painel do Administrador, <?php echo $_SESSION['usuario_nome']; ?>!</h1>
    <!-- Adicione o conteúdo do painel de administração aqui -->
</body>
</html>
