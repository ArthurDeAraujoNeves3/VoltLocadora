<?php
class Funcionario {
    private $id;
    private $idAgencia;
    private $nome;
    private $cpf;
    private $cargo;
    private $email;
    private $telefone;
    private $dataAdmissao;
    private $ativo;

    public function __construct($idAgencia = '', $nome = '', $cpf = '', $cargo = '', $email = '', $telefone = '', $dataAdmissao = '', $ativo = 1) {
        $this->idAgencia    = $idAgencia;
        $this->nome         = $nome;
        $this->cpf          = $cpf;
        $this->cargo        = $cargo;
        $this->email        = $email;
        $this->telefone     = $telefone;
        $this->dataAdmissao = $dataAdmissao;
        $this->ativo        = $ativo;
    }

    public function getId()           { return $this->id; }
    public function getIdAgencia()    { return $this->idAgencia; }
    public function getNome()         { return $this->nome; }
    public function getCpf()          { return $this->cpf; }
    public function getCargo()        { return $this->cargo; }
    public function getEmail()        { return $this->email; }
    public function getTelefone()     { return $this->telefone; }
    public function getDataAdmissao() { return $this->dataAdmissao; }
    public function getAtivo()        { return $this->ativo; }

    public function setId($v)           { $this->id = $v; }
    public function setIdAgencia($v)    { $this->idAgencia = $v; }
    public function setNome($v)         { $this->nome = $v; }
    public function setCpf($v)          { $this->cpf = $v; }
    public function setCargo($v)        { $this->cargo = $v; }
    public function setEmail($v)        { $this->email = $v; }
    public function setTelefone($v)     { $this->telefone = $v; }
    public function setDataAdmissao($v) { $this->dataAdmissao = $v; }
    public function setAtivo($v)        { $this->ativo = $v; }
}
