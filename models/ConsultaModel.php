<?php

namespace Models;

use PDO;

class ConsultaModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function agendarConsulta($paciente_id, $veterinario_id, $data, $hora, $descricao) {
        $sql = "INSERT INTO consultas (paciente_id, veterinario_id, data, hora, descricao) VALUES (:paciente_id, :veterinario_id, :data, :hora, :descricao)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':veterinario_id', $veterinario_id);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':descricao', $descricao);

        return $stmt->execute();
    }

    public function listarConsultas() {
        $sql = "SELECT * FROM consultas";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
