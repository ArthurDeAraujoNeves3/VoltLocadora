<?php

require_once 'model/Avaria.php';
require_once 'dao/AvariaDAO.php';

class AvariaController
{

    private $dao;

    public function __construct($dao = null)
    {
        $this->dao = $dao ?? new AvariaDAO();
    }

    public function salvar(
        $idLocacao,
        $idFuncionario,
        $descricao,
        $valorReparo,
        $status,
        $cobradoCliente
    ) {

        $obj = new Avaria(
            $idLocacao,
            $idFuncionario,
            $descricao,
            $valorReparo,
            $status,
            $cobradoCliente
        );

        $this->dao->salvar($obj);
    }

    public function listar()
    {
        return $this->dao->listar();
    }

    public function atualizar(
        $id,
        $idLocacao,
        $idFuncionario,
        $descricao,
        $valorReparo,
        $status,
        $cobradoCliente
    ) {

        $obj = new Avaria(
            $idLocacao,
            $idFuncionario,
            $descricao,
            $valorReparo,
            $status,
            $cobradoCliente
        );

        $obj->setId($id);

        $this->dao->atualizar($obj);
    }

    public function deletar($id)
    {
        $this->dao->deletar($id);
    }

    public function buscarPorId($id)
    {
        return $this->dao->buscarPorId($id);
    }
}
