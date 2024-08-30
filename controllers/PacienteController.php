<?php

namespace Controllers;

use Models\PacienteModel;

class PacienteController {
    private $pacienteModel;

    public function __construct($db) {
        $this->pacienteModel = new PacienteModel($db);
    }

    public function adicionarPaciente($nome, $especie, $raca, $idade, $historico) {
        return $this->pacienteModel->adicionarPaciente($nome, $especie, $raca, $idade, $historico);
    }

    public function listarPacientes() {
        return $this->pacienteModel->listarPacientes();
    }

    public function atualizarPaciente($id, $nome, $especie, $raca, $idade, $historico) {
        return $this->pacienteModel->atualizarPaciente($id, $nome, $especie, $raca, $idade, $historico);
    }
}
