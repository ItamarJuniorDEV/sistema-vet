<?php

namespace Models;

use PDO;

class PacienteModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function adicionarPaciente($nome, $especie, $raca, $idade, $historico) {
        $sql = "INSERT INTO pacientes (nome, especie, raca, idade, historico) VALUES (:nome, :especie, :raca, :idade, :historico)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':especie', $especie);
        $stmt->bindParam(':raca', $raca);
        $stmt->bindParam(':idade', $idade);
        $stmt->bindParam(':historico', $historico);

        return $stmt->execute();
    }

    public function listarPacientes() {
        $sql = "SELECT * FROM pacientes";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarPaciente($id, $nome, $especie, $raca, $idade, $historico) {
        $sql = "UPDATE pacientes SET nome = :nome, especie = :especie, raca = :raca, idade = :idade, historico = :historico WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':especie', $especie);
        $stmt->bindParam(':raca', $raca);
        $stmt->bindParam(':idade', $idade);
        $stmt->bindParam(':historico', $historico);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
