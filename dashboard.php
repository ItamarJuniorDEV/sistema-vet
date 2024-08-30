<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Usuário</title>
</head>
<body>
    <h1>Bem-vindo ao Painel do Usuário, <?php echo $_SESSION['usuario_nome']; ?>!</h1>
    <!-- Adicione o conteúdo do painel de usuário aqui -->
</body>
</html>
