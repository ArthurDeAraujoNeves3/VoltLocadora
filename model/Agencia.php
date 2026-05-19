<?php
class Agencia
{
    private $id;
    private $nome;
    private $cnpj;
    private $endereco;
    private $cidade;
    private $estado;
    private $cep;
    private $telefone;
    private $email;
    private $matriz;

    public function __construct($nome = '', $cnpj = '', $endereco = '', $cidade = '', $estado = '', $cep = '', $telefone = '', $email = '', $matriz = 0)
    {
        $this->nome     = $nome;
        $this->cnpj     = $cnpj;
        $this->endereco = $endereco;
        $this->cidade   = $cidade;
        $this->estado   = $estado;
        $this->cep      = $cep;
        $this->telefone = $telefone;
        $this->email    = $email;
        $this->matriz   = $matriz;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNome()
    {
        return $this->nome;
    }
    public function getCnpj()
    {
        return $this->cnpj;
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
    public function getTelefone()
    {
        return $this->telefone;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getMatriz()
    {
        return $this->matriz;
    }

    public function setId($v)
    {
        $this->id = $v;
    }
    public function setNome($v)
    {
        $this->nome = $v;
    }
    public function setCnpj($v)
    {
        $this->cnpj = $v;
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
    public function setTelefone($v)
    {
        $this->telefone = $v;
    }
    public function setEmail($v)
    {
        $this->email = $v;
    }
    public function setMatriz($v)
    {
        $this->matriz = $v;
    }
}
