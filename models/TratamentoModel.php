<?php

namespace Models;

use PDO;

class TratamentoModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Adiciona um novo tratamento ao banco de dados
    public function adicionarTratamento($pacienteId, $tipo, $descricao, $dataTratamento) {
        $sql = "INSERT INTO tratamentos (paciente_id, tipo, descricao, data_tratamento) VALUES (:paciente_id, :tipo, :descricao, :data_tratamento)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':paciente_id', $pacienteId);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':data_tratamento', $dataTratamento);
        return $stmt->execute();
    }

    // Atualiza os detalhes de um tratamento existente
    public function atualizarTratamento($tratamentoId, $tipo, $descricao, $dataTratamento) {
        $sql = "UPDATE tratamentos SET tipo = :tipo, descricao = :descricao, data_tratamento = :data_tratamento WHERE id = :tratamento_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':data_tratamento', $dataTratamento);
        $stmt->bindParam(':tratamento_id', $tratamentoId);
        return $stmt->execute();
    }

    // Retorna uma lista de todos os tratamentos
    public function listarTratamentos() {
        $sql = "SELECT * FROM tratamentos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
