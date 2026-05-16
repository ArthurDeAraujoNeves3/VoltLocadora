<?php

require_once 'model/Seguro.php';
require_once 'dao/SeguroDAO.php';

class SeguroController
{

    private $dao;

    public function __construct($dao = null)
    {
        $this->dao = $dao ?? new SeguroDAO();
    }

    public function salvar(
        $idLocacao,
        $tipo,
        $cobertura,
        $valorFranquia,
        $valorDiaria,
        $apolice
    ) {

        $obj = new Seguro(
            $idLocacao,
            $tipo,
            $cobertura,
            $valorFranquia,
            $valorDiaria,
            $apolice
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
        $tipo,
        $cobertura,
        $valorFranquia,
        $valorDiaria,
        $apolice
    ) {

        $obj = new Seguro(
            $idLocacao,
            $tipo,
            $cobertura,
            $valorFranquia,
            $valorDiaria,
            $apolice
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
