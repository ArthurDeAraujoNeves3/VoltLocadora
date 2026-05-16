<?php

require_once 'model/Pagamento.php';
require_once 'dao/PagamentoDAO.php';

class PagamentoController
{

    private $dao;

    public function __construct($dao = null)
    {
        $this->dao = $dao ?? new PagamentoDAO();
    }

    public function salvar(
        $idLocacao,
        $valor,
        $formaPagamento,
        $status,
        $codigoTransacao,
        $dataPagamento,
        $parcelas
    ) {

        $obj = new Pagamento(
            $idLocacao,
            $valor,
            $formaPagamento,
            $status,
            $codigoTransacao,
            $dataPagamento,
            $parcelas
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
        $valor,
        $formaPagamento,
        $status,
        $codigoTransacao,
        $dataPagamento,
        $parcelas
    ) {

        $obj = new Pagamento(
            $idLocacao,
            $valor,
            $formaPagamento,
            $status,
            $codigoTransacao,
            $dataPagamento,
            $parcelas
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
