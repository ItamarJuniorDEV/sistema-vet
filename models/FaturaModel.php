<?php

namespace Models;

use PDO;

class FaturaModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para criar uma fatura
    public function criarFatura($pacienteId, $valor, $descricao, $dataPagamento) {
        $sql = "INSERT INTO faturas (paciente_id, valor, descricao, data_pagamento) VALUES (:paciente_id, :valor, :descricao, :data_pagamento)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':paciente_id', $pacienteId);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':data_pagamento', $dataPagamento);

        return $stmt->execute();
    }

    // Método para listar todas as faturas
    public function listarFaturas() {
        $sql = "SELECT * FROM faturas";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
