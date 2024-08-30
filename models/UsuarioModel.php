<?php

namespace Models;

use PDO;

class UsuarioModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criarUsuario($nome, $email, $senha, $funcao) {
        $sql = "INSERT INTO usuarios (nome, email, senha, funcao) VALUES (:nome, :email, :senha, :funcao)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', password_hash($senha, PASSWORD_DEFAULT));
        $stmt->bindParam(':funcao', $funcao);

        return $stmt->execute();
    }

    public function autenticarUsuario($email, $senha) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }

        return null;
    }

    public function atualizarUsuario($id, $nome, $email, $funcao) {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, funcao = :funcao WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':funcao', $funcao);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
