<?php
class Veiculo {
    private $id;
    private $idCategoria;
    private $idMarca;
    private $modelo;
    private $anoFabricacao;
    private $anoModelo;
    private $placa;
    private $chassi;
    private $cor;
    private $valorDiaria;
    private $status;
    private $quilometragem;
    private $combustivel;
    private $numPortas;
    private $capacidadePassageiros;

    public function __construct($idCategoria = '', $idMarca = '', $modelo = '', $anoFabricacao = '', $anoModelo = '', $placa = '', $chassi = '', $cor = '', $valorDiaria = 0, $status = 'disponivel', $quilometragem = 0, $combustivel = 'flex', $numPortas = 4, $capacidadePassageiros = 5) {
        $this->idCategoria           = $idCategoria;
        $this->idMarca               = $idMarca;
        $this->modelo                = $modelo;
        $this->anoFabricacao         = $anoFabricacao;
        $this->anoModelo             = $anoModelo;
        $this->placa                 = $placa;
        $this->chassi                = $chassi;
        $this->cor                   = $cor;
        $this->valorDiaria           = $valorDiaria;
        $this->status                = $status;
        $this->quilometragem         = $quilometragem;
        $this->combustivel           = $combustivel;
        $this->numPortas             = $numPortas;
        $this->capacidadePassageiros = $capacidadePassageiros;
    }

    public function getId()                    { return $this->id; }
    public function getIdCategoria()           { return $this->idCategoria; }
    public function getIdMarca()               { return $this->idMarca; }
    public function getModelo()                { return $this->modelo; }
    public function getAnoFabricacao()         { return $this->anoFabricacao; }
    public function getAnoModelo()             { return $this->anoModelo; }
    public function getPlaca()                 { return $this->placa; }
    public function getChassi()                { return $this->chassi; }
    public function getCor()                   { return $this->cor; }
    public function getValorDiaria()           { return $this->valorDiaria; }
    public function getStatus()                { return $this->status; }
    public function getQuilometragem()         { return $this->quilometragem; }
    public function getCombustivel()           { return $this->combustivel; }
    public function getNumPortas()             { return $this->numPortas; }
    public function getCapacidadePassageiros() { return $this->capacidadePassageiros; }

    public function setId($v)                    { $this->id = $v; }
    public function setIdCategoria($v)           { $this->idCategoria = $v; }
    public function setIdMarca($v)               { $this->idMarca = $v; }
    public function setModelo($v)                { $this->modelo = $v; }
    public function setAnoFabricacao($v)         { $this->anoFabricacao = $v; }
    public function setAnoModelo($v)             { $this->anoModelo = $v; }
    public function setPlaca($v)                 { $this->placa = $v; }
    public function setChassi($v)                { $this->chassi = $v; }
    public function setCor($v)                   { $this->cor = $v; }
    public function setValorDiaria($v)           { $this->valorDiaria = $v; }
    public function setStatus($v)                { $this->status = $v; }
    public function setQuilometragem($v)         { $this->quilometragem = $v; }
    public function setCombustivel($v)           { $this->combustivel = $v; }
    public function setNumPortas($v)             { $this->numPortas = $v; }
    public function setCapacidadePassageiros($v) { $this->capacidadePassageiros = $v; }
}
