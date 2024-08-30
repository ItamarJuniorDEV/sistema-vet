<?php

namespace Models;

use PDO;
use PDOException;

class Database {
    private $host = 'localhost';  // Host do banco de dados
    private $db_name = 'sistema_vet';  // Nome do banco de dados
    private $username = 'root';  // Usuário do banco de dados
    private $password = '';  // Senha do banco de dados
    private $conn;

    // Método para obter uma conexão com o banco de dados
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erro de conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
