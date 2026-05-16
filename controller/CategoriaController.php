<?php
require_once 'model/Categoria.php';
require_once 'dao/CategoriaDAO.php';

class CategoriaController {
    public function salvar($nome, $descricao, $codigo) {
        $obj = new Categoria($nome, $descricao, $codigo);
        $dao = new CategoriaDAO();
        $dao->salvar($obj);
    }

    public function listar() {
        $dao = new CategoriaDAO();
        return $dao->listar();
    }

    public function atualizar($id, $nome, $descricao, $codigo) {
        $obj = new Categoria($nome, $descricao, $codigo);
        $obj->setId($id);
        $dao = new CategoriaDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id) {
        $dao = new CategoriaDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id) {
        $dao = new CategoriaDAO();
        return $dao->buscarPorId($id);
    }
}
