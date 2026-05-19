<?php
class Categoria
{
    private $id;
    private $nome;
    private $descricao;
    private $codigo;

    public function __construct($nome = '', $descricao = '', $codigo = '')
    {
        $this->nome      = $nome;
        $this->descricao = $descricao;
        $this->codigo    = $codigo;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNome()
    {
        return $this->nome;
    }
    public function getDescricao()
    {
        return $this->descricao;
    }
    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setId($v)
    {
        $this->id = $v;
    }
    public function setNome($v)
    {
        $this->nome = $v;
    }
    public function setDescricao($v)
    {
        $this->descricao = $v;
    }
    public function setCodigo($v)
    {
        $this->codigo = $v;
    }
}
