<?php
require_once 'config/Conexao.php';
require_once 'model/Avaria.php';

class AvariaDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Avaria $obj)
    {
        $stmt = $this->conn->prepare("INSERT INTO avaria (id_locacao, id_funcionario, descricao, valor_reparo, status, cobrado_cliente) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$obj->getIdLocacao(), $obj->getIdFuncionario(), $obj->getDescricao(), $obj->getValorReparo(), $obj->getStatus(), $obj->getCobradoCliente()]);
    }

    public function listar()
    {
        $stmt = $this->conn->query("SELECT * FROM avaria ORDER BY data_registro DESC");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Avaria($row['id_locacao'], $row['id_funcionario'], $row['descricao'], $row['valor_reparo'], $row['status'], $row['cobrado_cliente']);
            $obj->setId($row['id_avaria']);
            $obj->setDataRegistro($row['data_registro']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Avaria $obj)
    {
        $stmt = $this->conn->prepare("UPDATE avaria SET id_locacao = ?, id_funcionario = ?, descricao = ?, valor_reparo = ?, status = ?, cobrado_cliente = ? WHERE id_avaria = ?");
        $stmt->execute([$obj->getIdLocacao(), $obj->getIdFuncionario(), $obj->getDescricao(), $obj->getValorReparo(), $obj->getStatus(), $obj->getCobradoCliente(), $obj->getId()]);
    }

    public function deletar($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM avaria WHERE id_avaria = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM avaria WHERE id_avaria = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Avaria($row['id_locacao'], $row['id_funcionario'], $row['descricao'], $row['valor_reparo'], $row['status'], $row['cobrado_cliente']);
            $obj->setId($row['id_avaria']);
            $obj->setDataRegistro($row['data_registro']);
            return $obj;
        }
        return null;
    }
}
