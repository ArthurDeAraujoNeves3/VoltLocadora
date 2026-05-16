<?php
require_once 'config/Conexao.php';
require_once 'model/Marca.php';

class MarcaDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Marca $obj) {
        $stmt = $this->conn->prepare("INSERT INTO marca (nome, pais_origem) VALUES (?, ?)");
        $stmt->execute([$obj->getNome(), $obj->getPaisOrigem()]);
    }

    public function listar() {
        $stmt = $this->conn->query("SELECT * FROM marca ORDER BY nome");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Marca($row['nome'], $row['pais_origem']);
            $obj->setId($row['id_marca']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Marca $obj) {
        $stmt = $this->conn->prepare("UPDATE marca SET nome = ?, pais_origem = ? WHERE id_marca = ?");
        $stmt->execute([$obj->getNome(), $obj->getPaisOrigem(), $obj->getId()]);
    }

    public function deletar($id) {
        $stmt = $this->conn->prepare("DELETE FROM marca WHERE id_marca = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM marca WHERE id_marca = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Marca($row['nome'], $row['pais_origem']);
            $obj->setId($row['id_marca']);
            return $obj;
        }
        return null;
    }
}
