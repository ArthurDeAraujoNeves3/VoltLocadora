<?php
class Locacao
{
    private $id;
    private $idCliente;
    private $idVeiculo;
    private $idFuncionario;
    private $idAgenciaRetirada;
    private $idAgenciaDevolucao;
    private $dataRetirada;
    private $dataDevolucaoPrevista;
    private $dataDevolucaoReal;
    private $valorDiaria;
    private $totalDias;
    private $valorTotal;
    private $status;
    private $observacoes;
    private $dataCriacao;

    public function __construct($idCliente = '', $idVeiculo = '', $idFuncionario = '', $idAgenciaRetirada = '', $idAgenciaDevolucao = '', $dataRetirada = '', $dataDevolucaoPrevista = '', $dataDevolucaoReal = '', $valorDiaria = 0, $totalDias = 0, $valorTotal = 0, $status = 'aberta', $observacoes = '')
    {
        $this->idCliente            = $idCliente;
        $this->idVeiculo            = $idVeiculo;
        $this->idFuncionario        = $idFuncionario;
        $this->idAgenciaRetirada    = $idAgenciaRetirada;
        $this->idAgenciaDevolucao   = $idAgenciaDevolucao;
        $this->dataRetirada         = $dataRetirada;
        $this->dataDevolucaoPrevista = $dataDevolucaoPrevista;
        $this->dataDevolucaoReal    = $dataDevolucaoReal;
        $this->valorDiaria          = $valorDiaria;
        $this->totalDias            = $totalDias;
        $this->valorTotal           = $valorTotal;
        $this->status               = $status;
        $this->observacoes          = $observacoes;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getIdCliente()
    {
        return $this->idCliente;
    }
    public function getIdVeiculo()
    {
        return $this->idVeiculo;
    }
    public function getIdFuncionario()
    {
        return $this->idFuncionario;
    }
    public function getIdAgenciaRetirada()
    {
        return $this->idAgenciaRetirada;
    }
    public function getIdAgenciaDevolucao()
    {
        return $this->idAgenciaDevolucao;
    }
    public function getDataRetirada()
    {
        return $this->dataRetirada;
    }
    public function getDataDevolucaoPrevista()
    {
        return $this->dataDevolucaoPrevista;
    }
    public function getDataDevolucaoReal()
    {
        return $this->dataDevolucaoReal;
    }
    public function getValorDiaria()
    {
        return $this->valorDiaria;
    }
    public function getTotalDias()
    {
        return $this->totalDias;
    }
    public function getValorTotal()
    {
        return $this->valorTotal;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getObservacoes()
    {
        return $this->observacoes;
    }
    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function setId($v)
    {
        $this->id = $v;
    }
    public function setIdCliente($v)
    {
        $this->idCliente = $v;
    }
    public function setIdVeiculo($v)
    {
        $this->idVeiculo = $v;
    }
    public function setIdFuncionario($v)
    {
        $this->idFuncionario = $v;
    }
    public function setIdAgenciaRetirada($v)
    {
        $this->idAgenciaRetirada = $v;
    }
    public function setIdAgenciaDevolucao($v)
    {
        $this->idAgenciaDevolucao = $v;
    }
    public function setDataRetirada($v)
    {
        $this->dataRetirada = $v;
    }
    public function setDataDevolucaoPrevista($v)
    {
        $this->dataDevolucaoPrevista = $v;
    }
    public function setDataDevolucaoReal($v)
    {
        $this->dataDevolucaoReal = $v;
    }
    public function setValorDiaria($v)
    {
        $this->valorDiaria = $v;
    }
    public function setTotalDias($v)
    {
        $this->totalDias = $v;
    }
    public function setValorTotal($v)
    {
        $this->valorTotal = $v;
    }
    public function setStatus($v)
    {
        $this->status = $v;
    }
    public function setObservacoes($v)
    {
        $this->observacoes = $v;
    }
    public function setDataCriacao($v)
    {
        $this->dataCriacao = $v;
    }
}
