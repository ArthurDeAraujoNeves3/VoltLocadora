<?php
require_once 'model/Seguro.php';
require_once 'dao/SeguroDAO.php';

class SeguroController {
    public function salvar($idLocacao, $tipo, $cobertura, $valorFranquia, $valorDiaria, $apolice) {
        $obj = new Seguro($idLocacao, $tipo, $cobertura, $valorFranquia, $valorDiaria, $apolice);
        $dao = new SeguroDAO();
        $dao->salvar($obj);
    }

    public function listar() {
        $dao = new SeguroDAO();
        return $dao->listar();
    }

    public function atualizar($id, $idLocacao, $tipo, $cobertura, $valorFranquia, $valorDiaria, $apolice) {
        $obj = new Seguro($idLocacao, $tipo, $cobertura, $valorFranquia, $valorDiaria, $apolice);
        $obj->setId($id);
        $dao = new SeguroDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id) {
        $dao = new SeguroDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id) {
        $dao = new SeguroDAO();
        return $dao->buscarPorId($id);
    }
}
