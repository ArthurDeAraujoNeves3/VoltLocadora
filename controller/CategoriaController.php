<?php

require_once 'model/Categoria.php';
require_once 'dao/CategoriaDAO.php';

class CategoriaController
{

    private $dao;

    public function __construct($dao = null)
    {
        $this->dao = $dao ?? new CategoriaDAO();
    }

    public function salvar($nome, $descricao, $codigo)
    {

        $obj = new Categoria(
            $nome,
            $descricao,
            $codigo
        );

        $this->dao->salvar($obj);
    }

    public function listar()
    {
        return $this->dao->listar();
    }

    public function atualizar($id, $nome, $descricao, $codigo)
    {

        $obj = new Categoria(
            $nome,
            $descricao,
            $codigo
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
