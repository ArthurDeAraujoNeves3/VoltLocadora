<?php
class Seguro {
    private $id;
    private $idLocacao;
    private $tipo;
    private $cobertura;
    private $valorFranquia;
    private $valorDiaria;
    private $apolice;

    public function __construct($idLocacao = '', $tipo = '', $cobertura = '', $valorFranquia = 0, $valorDiaria = 0, $apolice = '') {
        $this->idLocacao     = $idLocacao;
        $this->tipo          = $tipo;
        $this->cobertura     = $cobertura;
        $this->valorFranquia = $valorFranquia;
        $this->valorDiaria   = $valorDiaria;
        $this->apolice       = $apolice;
    }

    public function getId()           { return $this->id; }
    public function getIdLocacao()    { return $this->idLocacao; }
    public function getTipo()         { return $this->tipo; }
    public function getCobertura()    { return $this->cobertura; }
    public function getValorFranquia(){ return $this->valorFranquia; }
    public function getValorDiaria()  { return $this->valorDiaria; }
    public function getApolice()      { return $this->apolice; }

    public function setId($v)            { $this->id = $v; }
    public function setIdLocacao($v)     { $this->idLocacao = $v; }
    public function setTipo($v)          { $this->tipo = $v; }
    public function setCobertura($v)     { $this->cobertura = $v; }
    public function setValorFranquia($v) { $this->valorFranquia = $v; }
    public function setValorDiaria($v)   { $this->valorDiaria = $v; }
    public function setApolice($v)       { $this->apolice = $v; }
}
