<?php
require_once 'model/Pagamento.php';
require_once 'dao/PagamentoDAO.php';

class PagamentoController
{
    public function salvar($idLocacao, $valor, $formaPagamento, $status, $codigoTransacao, $dataPagamento, $parcelas)
    {
        $obj = new Pagamento($idLocacao, $valor, $formaPagamento, $status, $codigoTransacao, $dataPagamento, $parcelas);
        $dao = new PagamentoDAO();
        $dao->salvar($obj);
    }

    public function listar()
    {
        $dao = new PagamentoDAO();
        return $dao->listar();
    }

    public function atualizar($id, $idLocacao, $valor, $formaPagamento, $status, $codigoTransacao, $dataPagamento, $parcelas)
    {
        $obj = new Pagamento($idLocacao, $valor, $formaPagamento, $status, $codigoTransacao, $dataPagamento, $parcelas);
        $obj->setId($id);
        $dao = new PagamentoDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id)
    {
        $dao = new PagamentoDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id)
    {
        $dao = new PagamentoDAO();
        return $dao->buscarPorId($id);
    }
}
