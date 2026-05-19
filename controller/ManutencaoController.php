<?php
require_once 'model/Manutencao.php';
require_once 'dao/ManutencaoDAO.php';

class ManutencaoController
{
    public function salvar($idVeiculo, $idFuncionario, $tipo, $descricao, $custo, $dataEntrada, $dataSaida, $status, $quilometragemEntrada)
    {
        $obj = new Manutencao($idVeiculo, $idFuncionario, $tipo, $descricao, $custo, $dataEntrada, $dataSaida, $status, $quilometragemEntrada);
        $dao = new ManutencaoDAO();
        $dao->salvar($obj);
    }

    public function listar()
    {
        $dao = new ManutencaoDAO();
        return $dao->listar();
    }

    public function atualizar($id, $idVeiculo, $idFuncionario, $tipo, $descricao, $custo, $dataEntrada, $dataSaida, $status, $quilometragemEntrada)
    {
        $obj = new Manutencao($idVeiculo, $idFuncionario, $tipo, $descricao, $custo, $dataEntrada, $dataSaida, $status, $quilometragemEntrada);
        $obj->setId($id);
        $dao = new ManutencaoDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id)
    {
        $dao = new ManutencaoDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id)
    {
        $dao = new ManutencaoDAO();
        return $dao->buscarPorId($id);
    }
}
