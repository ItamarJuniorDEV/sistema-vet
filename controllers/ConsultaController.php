<?php

namespace Controllers;

use Models\ConsultaModel;

class ConsultaController {
    private $consultaModel;

    public function __construct($db) {
        $this->consultaModel = new ConsultaModel($db);
    }

    public function agendarConsulta($paciente_id, $veterinario_id, $data, $hora, $descricao) {
        return $this->consultaModel->agendarConsulta($paciente_id, $veterinario_id, $data, $hora, $descricao);
    }

    public function listarConsultas() {
        return $this->consultaModel->listarConsultas();
    }
}
