<?php
$senha = '1954bola'; // Substitua por uma senha forte de sua escolha
$senha_hash = password_hash($senha, PASSWORD_BCRYPT);
echo $senha_hash;
?>