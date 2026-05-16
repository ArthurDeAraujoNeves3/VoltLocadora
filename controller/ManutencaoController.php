<?php

require_once 'model/Manutencao.php';
require_once 'dao/ManutencaoDAO.php';

class ManutencaoController
{

    private $dao;

    public function __construct($dao = null)
    {
        $this->dao = $dao ?? new ManutencaoDAO();
    }

    public function salvar(
        $idVeiculo,
        $idFuncionario,
        $tipo,
        $descricao,
        $custo,
        $dataEntrada,
        $dataSaida,
        $status,
        $quilometragemEntrada
    ) {

        $obj = new Manutencao(
            $idVeiculo,
            $idFuncionario,
            $tipo,
            $descricao,
            $custo,
            $dataEntrada,
            $dataSaida,
            $status,
            $quilometragemEntrada
        );

        $this->dao->salvar($obj);
    }

    public function listar()
    {
        return $this->dao->listar();
    }

    public function atualizar(
        $id,
        $idVeiculo,
        $idFuncionario,
        $tipo,
        $descricao,
        $custo,
        $dataEntrada,
        $dataSaida,
        $status,
        $quilometragemEntrada
    ) {

        $obj = new Manutencao(
            $idVeiculo,
            $idFuncionario,
            $tipo,
            $descricao,
            $custo,
            $dataEntrada,
            $dataSaida,
            $status,
            $quilometragemEntrada
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
