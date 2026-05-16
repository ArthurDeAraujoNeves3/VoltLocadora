<?php
require_once 'config/Conexao.php';
require_once 'model/Agencia.php';

class AgenciaDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Agencia $obj) {
        $stmt = $this->conn->prepare("INSERT INTO agencia (nome, cnpj, endereco, cidade, estado, cep, telefone, email, matriz) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$obj->getNome(), $obj->getCnpj(), $obj->getEndereco(), $obj->getCidade(), $obj->getEstado(), $obj->getCep(), $obj->getTelefone(), $obj->getEmail(), $obj->getMatriz()]);
    }

    public function listar() {
        $stmt = $this->conn->query("SELECT * FROM agencia ORDER BY nome");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Agencia($row['nome'], $row['cnpj'], $row['endereco'], $row['cidade'], $row['estado'], $row['cep'], $row['telefone'], $row['email'], $row['matriz']);
            $obj->setId($row['id_agencia']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Agencia $obj) {
        $stmt = $this->conn->prepare("UPDATE agencia SET nome = ?, cnpj = ?, endereco = ?, cidade = ?, estado = ?, cep = ?, telefone = ?, email = ?, matriz = ? WHERE id_agencia = ?");
        $stmt->execute([$obj->getNome(), $obj->getCnpj(), $obj->getEndereco(), $obj->getCidade(), $obj->getEstado(), $obj->getCep(), $obj->getTelefone(), $obj->getEmail(), $obj->getMatriz(), $obj->getId()]);
    }

    public function deletar($id) {
        $stmt = $this->conn->prepare("DELETE FROM agencia WHERE id_agencia = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM agencia WHERE id_agencia = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Agencia($row['nome'], $row['cnpj'], $row['endereco'], $row['cidade'], $row['estado'], $row['cep'], $row['telefone'], $row['email'], $row['matriz']);
            $obj->setId($row['id_agencia']);
            return $obj;
        }
        return null;
    }
}
