<?php

require_once 'model/Agencia.php';
require_once 'dao/AgenciaDAO.php';

class AgenciaController
{

    private $dao;

    public function __construct($dao = null)
    {
        $this->dao = $dao ?? new AgenciaDAO();
    }

    public function salvar($nome, $cnpj, $endereco, $cidade, $estado, $cep, $telefone, $email, $matriz)
    {
        $obj = new Agencia(
            $nome,
            $cnpj,
            $endereco,
            $cidade,
            $estado,
            $cep,
            $telefone,
            $email,
            $matriz
        );

        $this->dao->salvar($obj);
    }

    public function listar()
    {
        return $this->dao->listar();
    }

    public function atualizar($id, $nome, $cnpj, $endereco, $cidade, $estado, $cep, $telefone, $email, $matriz)
    {

        $obj = new Agencia(
            $nome,
            $cnpj,
            $endereco,
            $cidade,
            $estado,
            $cep,
            $telefone,
            $email,
            $matriz
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
