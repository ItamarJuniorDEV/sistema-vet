<?php

namespace Controllers;

use Models\UsuarioModel;

class UsuarioController {
    private $usuarioModel;

    public function __construct($db) {
        $this->usuarioModel = new UsuarioModel($db);
    }

    public function registrar($nome, $email, $senha, $funcao) {
        return $this->usuarioModel->criarUsuario($nome, $email, $senha, $funcao);
    }

    public function login($email, $senha) {
        $usuario = $this->usuarioModel->autenticarUsuario($email, $senha);
        if ($usuario) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_funcao'] = $usuario['funcao'];
            return true;
        }
        return false;
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: login.php");
    }

    public function atualizarUsuario($id, $nome, $email, $funcao) {
        return $this->usuarioModel->atualizarUsuario($id, $nome, $email, $funcao);
    }
}
