<?php
class Avaria {
    private $id;
    private $idLocacao;
    private $idFuncionario;
    private $descricao;
    private $valorReparo;
    private $status;
    private $dataRegistro;
    private $cobradoCliente;

    public function __construct($idLocacao = '', $idFuncionario = '', $descricao = '', $valorReparo = 0, $status = 'pendente', $cobradoCliente = 0) {
        $this->idLocacao      = $idLocacao;
        $this->idFuncionario  = $idFuncionario;
        $this->descricao      = $descricao;
        $this->valorReparo    = $valorReparo;
        $this->status         = $status;
        $this->cobradoCliente = $cobradoCliente;
    }

    public function getId()             { return $this->id; }
    public function getIdLocacao()      { return $this->idLocacao; }
    public function getIdFuncionario()  { return $this->idFuncionario; }
    public function getDescricao()      { return $this->descricao; }
    public function getValorReparo()    { return $this->valorReparo; }
    public function getStatus()         { return $this->status; }
    public function getDataRegistro()   { return $this->dataRegistro; }
    public function getCobradoCliente() { return $this->cobradoCliente; }

    public function setId($v)             { $this->id = $v; }
    public function setIdLocacao($v)      { $this->idLocacao = $v; }
    public function setIdFuncionario($v)  { $this->idFuncionario = $v; }
    public function setDescricao($v)      { $this->descricao = $v; }
    public function setValorReparo($v)    { $this->valorReparo = $v; }
    public function setStatus($v)         { $this->status = $v; }
    public function setDataRegistro($v)   { $this->dataRegistro = $v; }
    public function setCobradoCliente($v) { $this->cobradoCliente = $v; }
}
