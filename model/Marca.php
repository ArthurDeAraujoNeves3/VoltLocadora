<?php
class Marca {
    private $id;
    private $nome;
    private $paisOrigem;

    public function __construct($nome = '', $paisOrigem = '') {
        $this->nome       = $nome;
        $this->paisOrigem = $paisOrigem;
    }

    public function getId()          { return $this->id; }
    public function getNome()        { return $this->nome; }
    public function getPaisOrigem()  { return $this->paisOrigem; }

    public function setId($v)          { $this->id = $v; }
    public function setNome($v)        { $this->nome = $v; }
    public function setPaisOrigem($v)  { $this->paisOrigem = $v; }
}
