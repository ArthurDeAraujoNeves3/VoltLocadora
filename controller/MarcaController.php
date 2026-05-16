<?php

require_once 'model/Marca.php';
require_once 'dao/MarcaDAO.php';

class MarcaController
{

    private $dao;

    public function __construct($dao = null)
    {
        $this->dao = $dao ?? new MarcaDAO();
    }

    public function salvar($nome, $paisOrigem)
    {

        $obj = new Marca(
            $nome,
            $paisOrigem
        );

        $alredyExist = $this->dao->buscarPorNome($nome);

        if($alredyExist) {
            throw new RuntimeException(
                'Marca já existe.'
            );            
        }
        
        $this->dao->salvar($obj);
    }

    public function listar()
    {
        return $this->dao->listar();
    }

    public function atualizar($id, $nome, $paisOrigem)
    {

        $obj = new Marca(
            $nome,
            $paisOrigem
        );

        $obj->setId($id);

        $alredyExist = $this->dao->buscarPorNome($nome);        

        if($alredyExist && $alredyExist->getId() != $id) {
            throw new RuntimeException(
                'Já existe uma marca com esse nome.'
            );
        }

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
