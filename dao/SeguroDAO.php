<?php
require_once 'config/Conexao.php';
require_once 'model/Seguro.php';

class SeguroDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexao::getConn();
    }

    public function locacaoPossuiSeguro($idLocacao)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM seguro WHERE id_locacao = ?");
        $stmt->execute([$idLocacao]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function salvar(Seguro $obj)
    {
        if ($this->locacaoPossuiSeguro($obj->getIdLocacao())) {
            throw new RuntimeException('Esta locação já possui um seguro cadastrado. Cada locação admite apenas um seguro.');
        }
        $stmt = $this->conn->prepare("INSERT INTO seguro (id_locacao, tipo, cobertura, valor_franquia, valor_diaria, apolice) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$obj->getIdLocacao(), $obj->getTipo(), $obj->getCobertura(), $obj->getValorFranquia(), $obj->getValorDiaria(), $obj->getApolice()]);
    }

    public function listar()
    {
        $stmt = $this->conn->query("SELECT * FROM seguro ORDER BY id_seguro DESC");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Seguro($row['id_locacao'], $row['tipo'], $row['cobertura'], $row['valor_franquia'], $row['valor_diaria'], $row['apolice']);
            $obj->setId($row['id_seguro']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Seguro $obj)
    {
        $stmt = $this->conn->prepare("UPDATE seguro SET id_locacao = ?, tipo = ?, cobertura = ?, valor_franquia = ?, valor_diaria = ?, apolice = ? WHERE id_seguro = ?");
        $stmt->execute([$obj->getIdLocacao(), $obj->getTipo(), $obj->getCobertura(), $obj->getValorFranquia(), $obj->getValorDiaria(), $obj->getApolice(), $obj->getId()]);
    }

    public function deletar($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM seguro WHERE id_seguro = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM seguro WHERE id_seguro = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Seguro($row['id_locacao'], $row['tipo'], $row['cobertura'], $row['valor_franquia'], $row['valor_diaria'], $row['apolice']);
            $obj->setId($row['id_seguro']);
            return $obj;
        }
        return null;
    }
}
