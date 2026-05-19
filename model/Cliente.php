<?php
class Cliente
{
    private $id;
    private $nome;
    private $cpf;
    private $email;
    private $telefone;
    private $cnh;
    private $vencimentoCnh;
    private $endereco;
    private $cidade;
    private $estado;
    private $cep;
    private $dataCadastro;
    private $ativo;

    public function __construct($nome = '', $cpf = '', $email = '', $telefone = '', $cnh = '', $vencimentoCnh = '', $endereco = '', $cidade = '', $estado = '', $cep = '', $ativo = 1)
    {
        $this->nome          = $nome;
        $this->cpf           = $cpf;
        $this->email         = $email;
        $this->telefone      = $telefone;
        $this->cnh           = $cnh;
        $this->vencimentoCnh = $vencimentoCnh;
        $this->endereco      = $endereco;
        $this->cidade        = $cidade;
        $this->estado        = $estado;
        $this->cep           = $cep;
        $this->ativo         = $ativo;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNome()
    {
        return $this->nome;
    }
    public function getCpf()
    {
        return $this->cpf;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getTelefone()
    {
        return $this->telefone;
    }
    public function getCnh()
    {
        return $this->cnh;
    }
    public function getVencimentoCnh()
    {
        return $this->vencimentoCnh;
    }
    public function getEndereco()
    {
        return $this->endereco;
    }
    public function getCidade()
    {
        return $this->cidade;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getCep()
    {
        return $this->cep;
    }
    public function getDataCadastro()
    {
        return $this->dataCadastro;
    }
    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setId($v)
    {
        $this->id = $v;
    }
    public function setNome($v)
    {
        $this->nome = $v;
    }
    public function setCpf($v)
    {
        $this->cpf = $v;
    }
    public function setEmail($v)
    {
        $this->email = $v;
    }
    public function setTelefone($v)
    {
        $this->telefone = $v;
    }
    public function setCnh($v)
    {
        $this->cnh = $v;
    }
    public function setVencimentoCnh($v)
    {
        $this->vencimentoCnh = $v;
    }
    public function setEndereco($v)
    {
        $this->endereco = $v;
    }
    public function setCidade($v)
    {
        $this->cidade = $v;
    }
    public function setEstado($v)
    {
        $this->estado = $v;
    }
    public function setCep($v)
    {
        $this->cep = $v;
    }
    public function setDataCadastro($v)
    {
        $this->dataCadastro = $v;
    }
    public function setAtivo($v)
    {
        $this->ativo = $v;
    }
}
