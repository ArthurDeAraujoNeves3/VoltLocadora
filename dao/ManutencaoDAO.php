<?php
require_once 'config/Conexao.php';
require_once 'model/Manutencao.php';

class ManutencaoDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Manutencao $obj)
    {
        $stmt = $this->conn->prepare("INSERT INTO manutencao (id_veiculo, id_funcionario, tipo, descricao, custo, data_entrada, data_saida, status, quilometragem_entrada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$obj->getIdVeiculo(), $obj->getIdFuncionario(), $obj->getTipo(), $obj->getDescricao(), $obj->getCusto(), $obj->getDataEntrada(), $obj->getDataSaida() ?: null, $obj->getStatus(), $obj->getQuilometragemEntrada()]);
    }

    public function listar()
    {
        $stmt = $this->conn->query("SELECT * FROM manutencao ORDER BY data_entrada DESC");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Manutencao($row['id_veiculo'], $row['id_funcionario'], $row['tipo'], $row['descricao'], $row['custo'], $row['data_entrada'], $row['data_saida'], $row['status'], $row['quilometragem_entrada']);
            $obj->setId($row['id_manutencao']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Manutencao $obj)
    {
        $stmt = $this->conn->prepare("UPDATE manutencao SET id_veiculo = ?, id_funcionario = ?, tipo = ?, descricao = ?, custo = ?, data_entrada = ?, data_saida = ?, status = ?, quilometragem_entrada = ? WHERE id_manutencao = ?");
        $stmt->execute([$obj->getIdVeiculo(), $obj->getIdFuncionario(), $obj->getTipo(), $obj->getDescricao(), $obj->getCusto(), $obj->getDataEntrada(), $obj->getDataSaida() ?: null, $obj->getStatus(), $obj->getQuilometragemEntrada(), $obj->getId()]);
    }

    public function deletar($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM manutencao WHERE id_manutencao = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM manutencao WHERE id_manutencao = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Manutencao($row['id_veiculo'], $row['id_funcionario'], $row['tipo'], $row['descricao'], $row['custo'], $row['data_entrada'], $row['data_saida'], $row['status'], $row['quilometragem_entrada']);
            $obj->setId($row['id_manutencao']);
            return $obj;
        }
        return null;
    }
}
