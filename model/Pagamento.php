<?php
class Pagamento {
    private $id;
    private $idLocacao;
    private $valor;
    private $formaPagamento;
    private $status;
    private $codigoTransacao;
    private $dataPagamento;
    private $parcelas;

    public function __construct($idLocacao = '', $valor = 0, $formaPagamento = '', $status = 'pendente', $codigoTransacao = '', $dataPagamento = '', $parcelas = 1) {
        $this->idLocacao       = $idLocacao;
        $this->valor           = $valor;
        $this->formaPagamento  = $formaPagamento;
        $this->status          = $status;
        $this->codigoTransacao = $codigoTransacao;
        $this->dataPagamento   = $dataPagamento;
        $this->parcelas        = $parcelas;
    }

    public function getId()               { return $this->id; }
    public function getIdLocacao()        { return $this->idLocacao; }
    public function getValor()            { return $this->valor; }
    public function getFormaPagamento()   { return $this->formaPagamento; }
    public function getStatus()           { return $this->status; }
    public function getCodigoTransacao()  { return $this->codigoTransacao; }
    public function getDataPagamento()    { return $this->dataPagamento; }
    public function getParcelas()         { return $this->parcelas; }

    public function setId($v)               { $this->id = $v; }
    public function setIdLocacao($v)        { $this->idLocacao = $v; }
    public function setValor($v)            { $this->valor = $v; }
    public function setFormaPagamento($v)   { $this->formaPagamento = $v; }
    public function setStatus($v)           { $this->status = $v; }
    public function setCodigoTransacao($v)  { $this->codigoTransacao = $v; }
    public function setDataPagamento($v)    { $this->dataPagamento = $v; }
    public function setParcelas($v)         { $this->parcelas = $v; }
}
