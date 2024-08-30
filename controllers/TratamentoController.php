<?php

namespace Controllers;

use Models\TratamentoModel;

class TratamentoController {
    private $tratamentoModel;

    public function __construct($db) {
        $this->tratamentoModel = new TratamentoModel($db);
    }

    public function adicionarTratamento($pacienteId, $tipo, $descricao, $dataTratamento) {
        return $this->tratamentoModel->adicionarTratamento($pacienteId, $tipo, $descricao, $dataTratamento);
    }

    public function atualizarTratamento($tratamentoId, $tipo, $descricao, $dataTratamento) {
        return $this->tratamentoModel->atualizarTratamento($tratamentoId, $tipo, $descricao, $dataTratamento);
    }

    public function listarTratamentos() {
        return $this->tratamentoModel->listarTratamentos();
    }
}