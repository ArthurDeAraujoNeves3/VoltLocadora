<?php

require_once 'model/Cliente.php';
require_once 'dao/ClienteDAO.php';

class ClienteController
{

    private $dao;

    public function __construct($dao = null)
    {
        $this->dao = $dao ?? new ClienteDAO();
    }

    public function salvar(
        $nome,
        $cpf,
        $email,
        $telefone,
        $cnh,
        $vencimentoCnh,
        $endereco,
        $cidade,
        $estado,
        $cep,
        $ativo
    ) {

        $obj = new Cliente(
            $nome,
            $cpf,
            $email,
            $telefone,
            $cnh,
            $vencimentoCnh,
            $endereco,
            $cidade,
            $estado,
            $cep,
            $ativo
        );

        $this->dao->salvar($obj);
    }

    public function listar()
    {
        return $this->dao->listar();
    }

    public function atualizar(
        $id,
        $nome,
        $cpf,
        $email,
        $telefone,
        $cnh,
        $vencimentoCnh,
        $endereco,
        $cidade,
        $estado,
        $cep,
        $ativo
    ) {

        $obj = new Cliente(
            $nome,
            $cpf,
            $email,
            $telefone,
            $cnh,
            $vencimentoCnh,
            $endereco,
            $cidade,
            $estado,
            $cep,
            $ativo
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
