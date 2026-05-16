<?php
require_once 'config/Conexao.php';
require_once 'model/Locacao.php';

class LocacaoDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Locacao $obj) {
        $stmt = $this->conn->prepare("INSERT INTO locacao (id_cliente, id_veiculo, id_funcionario, id_agencia_retirada, id_agencia_devolucao, data_retirada, data_devolucao_prevista, data_devolucao_real, valor_diaria, total_dias, valor_total, status, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$obj->getIdCliente(), $obj->getIdVeiculo(), $obj->getIdFuncionario(), $obj->getIdAgenciaRetirada(), $obj->getIdAgenciaDevolucao(), $obj->getDataRetirada(), $obj->getDataDevolucaoPrevista(), $obj->getDataDevolucaoReal() ?: null, $obj->getValorDiaria(), $obj->getTotalDias(), $obj->getValorTotal(), $obj->getStatus(), $obj->getObservacoes()]);
    }

    public function listar() {
        $stmt = $this->conn->query("SELECT * FROM locacao ORDER BY data_criacao DESC");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = $this->hidratarObjeto($row);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Locacao $obj) {
        $stmt = $this->conn->prepare("UPDATE locacao SET id_cliente = ?, id_veiculo = ?, id_funcionario = ?, id_agencia_retirada = ?, id_agencia_devolucao = ?, data_retirada = ?, data_devolucao_prevista = ?, data_devolucao_real = ?, valor_diaria = ?, total_dias = ?, valor_total = ?, status = ?, observacoes = ? WHERE id_locacao = ?");
        $stmt->execute([$obj->getIdCliente(), $obj->getIdVeiculo(), $obj->getIdFuncionario(), $obj->getIdAgenciaRetirada(), $obj->getIdAgenciaDevolucao(), $obj->getDataRetirada(), $obj->getDataDevolucaoPrevista(), $obj->getDataDevolucaoReal() ?: null, $obj->getValorDiaria(), $obj->getTotalDias(), $obj->getValorTotal(), $obj->getStatus(), $obj->getObservacoes(), $obj->getId()]);
    }

    public function deletar($id) {
        $stmt = $this->conn->prepare("DELETE FROM locacao WHERE id_locacao = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM locacao WHERE id_locacao = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $this->hidratarObjeto($row);
        }
        return null;
    }

    public function veiculoPossuiLocacaoAberta($idVeiculo) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM locacao WHERE id_veiculo = ? AND status = 'aberta'");
        $stmt->execute([$idVeiculo]);
        return (int)$stmt->fetchColumn() > 0;
    }

    private function hidratarObjeto($row) {
        $obj = new Locacao($row['id_cliente'], $row['id_veiculo'], $row['id_funcionario'], $row['id_agencia_retirada'], $row['id_agencia_devolucao'], $row['data_retirada'], $row['data_devolucao_prevista'], $row['data_devolucao_real'], $row['valor_diaria'], $row['total_dias'], $row['valor_total'], $row['status'], $row['observacoes']);
        $obj->setId($row['id_locacao']);
        $obj->setDataCriacao($row['data_criacao']);
        return $obj;
    }
}
