<?php

namespace Controllers;

use Models\FaturaModel;

class FaturamentoController {
    private $faturaModel;

    public function __construct($db) {
        $this->faturaModel = new FaturaModel($db);
    }

    public function criarFatura($pacienteId, $valor, $descricao, $dataPagamento) {
        return $this->faturaModel->criarFatura($pacienteId, $valor, $descricao, $dataPagamento);
    }

    public function listarFaturas() {
        return $this->faturaModel->listarFaturas();
    }
}
