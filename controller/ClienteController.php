<?php
require_once 'model/Cliente.php';
require_once 'dao/ClienteDAO.php';

class ClienteController
{
    public function salvar($nome, $cpf, $email, $telefone, $cnh, $vencimentoCnh, $endereco, $cidade, $estado, $cep, $ativo)
    {
        $obj = new Cliente($nome, $cpf, $email, $telefone, $cnh, $vencimentoCnh, $endereco, $cidade, $estado, $cep, $ativo);
        $dao = new ClienteDAO();
        $dao->salvar($obj);
    }

    public function listar()
    {
        $dao = new ClienteDAO();
        return $dao->listar();
    }

    public function atualizar($id, $nome, $cpf, $email, $telefone, $cnh, $vencimentoCnh, $endereco, $cidade, $estado, $cep, $ativo)
    {
        $obj = new Cliente($nome, $cpf, $email, $telefone, $cnh, $vencimentoCnh, $endereco, $cidade, $estado, $cep, $ativo);
        $obj->setId($id);
        $dao = new ClienteDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id)
    {
        $dao = new ClienteDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id)
    {
        $dao = new ClienteDAO();
        return $dao->buscarPorId($id);
    }
}
