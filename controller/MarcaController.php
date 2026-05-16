<?php
require_once 'model/Marca.php';
require_once 'dao/MarcaDAO.php';

class MarcaController {
    public function salvar($nome, $paisOrigem) {
        $obj = new Marca($nome, $paisOrigem);
        $dao = new MarcaDAO();
        $dao->salvar($obj);
    }

    public function listar() {
        $dao = new MarcaDAO();
        return $dao->listar();
    }

    public function atualizar($id, $nome, $paisOrigem) {
        $obj = new Marca($nome, $paisOrigem);
        $obj->setId($id);
        $dao = new MarcaDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id) {
        $dao = new MarcaDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id) {
        $dao = new MarcaDAO();
        return $dao->buscarPorId($id);
    }
}
