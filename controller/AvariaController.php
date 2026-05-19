<?php
require_once 'model/Avaria.php';
require_once 'dao/AvariaDAO.php';

class AvariaController
{
    public function salvar($idLocacao, $idFuncionario, $descricao, $valorReparo, $status, $cobradoCliente)
    {
        $obj = new Avaria($idLocacao, $idFuncionario, $descricao, $valorReparo, $status, $cobradoCliente);
        $dao = new AvariaDAO();
        $dao->salvar($obj);
    }

    public function listar()
    {
        $dao = new AvariaDAO();
        return $dao->listar();
    }

    public function atualizar($id, $idLocacao, $idFuncionario, $descricao, $valorReparo, $status, $cobradoCliente)
    {
        $obj = new Avaria($idLocacao, $idFuncionario, $descricao, $valorReparo, $status, $cobradoCliente);
        $obj->setId($id);
        $dao = new AvariaDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id)
    {
        $dao = new AvariaDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id)
    {
        $dao = new AvariaDAO();
        return $dao->buscarPorId($id);
    }
}
