<?php

require_once 'model/Funcionario.php';
require_once 'dao/FuncionarioDAO.php';

class FuncionarioController
{

    private $dao;

    public function __construct($dao = null)
    {
        $this->dao = $dao ?? new FuncionarioDAO();
    }

    public function salvar(
        $idAgencia,
        $nome,
        $cpf,
        $cargo,
        $email,
        $telefone,
        $dataAdmissao,
        $ativo
    ) {

        $obj = new Funcionario(
            $idAgencia,
            $nome,
            $cpf,
            $cargo,
            $email,
            $telefone,
            $dataAdmissao,
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
        $idAgencia,
        $nome,
        $cpf,
        $cargo,
        $email,
        $telefone,
        $dataAdmissao,
        $ativo
    ) {

        $obj = new Funcionario(
            $idAgencia,
            $nome,
            $cpf,
            $cargo,
            $email,
            $telefone,
            $dataAdmissao,
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
