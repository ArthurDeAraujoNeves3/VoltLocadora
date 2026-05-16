<?php
require_once 'config/Conexao.php';
require_once 'model/Veiculo.php';

class VeiculoDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Veiculo $obj) {
        $stmt = $this->conn->prepare("INSERT INTO veiculo (id_categoria, id_marca, modelo, ano_fabricacao, ano_modelo, placa, chassi, cor, valor_diaria, status, quilometragem, combustivel, num_portas, capacidade_passageiros) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$obj->getIdCategoria(), $obj->getIdMarca(), $obj->getModelo(), $obj->getAnoFabricacao(), $obj->getAnoModelo(), $obj->getPlaca(), $obj->getChassi(), $obj->getCor(), $obj->getValorDiaria(), $obj->getStatus(), $obj->getQuilometragem(), $obj->getCombustivel(), $obj->getNumPortas(), $obj->getCapacidadePassageiros()]);
    }

    public function listar() {
        $stmt = $this->conn->query("SELECT * FROM veiculo ORDER BY modelo");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Veiculo($row['id_categoria'], $row['id_marca'], $row['modelo'], $row['ano_fabricacao'], $row['ano_modelo'], $row['placa'], $row['chassi'], $row['cor'], $row['valor_diaria'], $row['status'], $row['quilometragem'], $row['combustivel'], $row['num_portas'], $row['capacidade_passageiros']);
            $obj->setId($row['id_veiculo']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Veiculo $obj) {
        $stmt = $this->conn->prepare("UPDATE veiculo SET id_categoria = ?, id_marca = ?, modelo = ?, ano_fabricacao = ?, ano_modelo = ?, placa = ?, chassi = ?, cor = ?, valor_diaria = ?, status = ?, quilometragem = ?, combustivel = ?, num_portas = ?, capacidade_passageiros = ? WHERE id_veiculo = ?");
        $stmt->execute([$obj->getIdCategoria(), $obj->getIdMarca(), $obj->getModelo(), $obj->getAnoFabricacao(), $obj->getAnoModelo(), $obj->getPlaca(), $obj->getChassi(), $obj->getCor(), $obj->getValorDiaria(), $obj->getStatus(), $obj->getQuilometragem(), $obj->getCombustivel(), $obj->getNumPortas(), $obj->getCapacidadePassageiros(), $obj->getId()]);
    }

    public function deletar($id) {
        $stmt = $this->conn->prepare("DELETE FROM veiculo WHERE id_veiculo = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM veiculo WHERE id_veiculo = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Veiculo($row['id_categoria'], $row['id_marca'], $row['modelo'], $row['ano_fabricacao'], $row['ano_modelo'], $row['placa'], $row['chassi'], $row['cor'], $row['valor_diaria'], $row['status'], $row['quilometragem'], $row['combustivel'], $row['num_portas'], $row['capacidade_passageiros']);
            $obj->setId($row['id_veiculo']);
            return $obj;
        }
        return null;
    }
}
