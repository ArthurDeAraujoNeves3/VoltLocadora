<?php
class Manutencao {
    private $id;
    private $idVeiculo;
    private $idFuncionario;
    private $tipo;
    private $descricao;
    private $custo;
    private $dataEntrada;
    private $dataSaida;
    private $status;
    private $quilometragemEntrada;

    public function __construct($idVeiculo = '', $idFuncionario = '', $tipo = 'preventiva', $descricao = '', $custo = 0, $dataEntrada = '', $dataSaida = '', $status = 'aberta', $quilometragemEntrada = 0) {
        $this->idVeiculo            = $idVeiculo;
        $this->idFuncionario        = $idFuncionario;
        $this->tipo                 = $tipo;
        $this->descricao            = $descricao;
        $this->custo                = $custo;
        $this->dataEntrada          = $dataEntrada;
        $this->dataSaida            = $dataSaida;
        $this->status               = $status;
        $this->quilometragemEntrada = $quilometragemEntrada;
    }

    public function getId()                   { return $this->id; }
    public function getIdVeiculo()            { return $this->idVeiculo; }
    public function getIdFuncionario()        { return $this->idFuncionario; }
    public function getTipo()                 { return $this->tipo; }
    public function getDescricao()            { return $this->descricao; }
    public function getCusto()                { return $this->custo; }
    public function getDataEntrada()          { return $this->dataEntrada; }
    public function getDataSaida()            { return $this->dataSaida; }
    public function getStatus()               { return $this->status; }
    public function getQuilometragemEntrada() { return $this->quilometragemEntrada; }

    public function setId($v)                   { $this->id = $v; }
    public function setIdVeiculo($v)            { $this->idVeiculo = $v; }
    public function setIdFuncionario($v)        { $this->idFuncionario = $v; }
    public function setTipo($v)                 { $this->tipo = $v; }
    public function setDescricao($v)            { $this->descricao = $v; }
    public function setCusto($v)                { $this->custo = $v; }
    public function setDataEntrada($v)          { $this->dataEntrada = $v; }
    public function setDataSaida($v)            { $this->dataSaida = $v; }
    public function setStatus($v)               { $this->status = $v; }
    public function setQuilometragemEntrada($v) { $this->quilometragemEntrada = $v; }
}
