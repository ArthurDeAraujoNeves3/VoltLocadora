<?php
require_once 'config/Conexao.php';
require_once 'model/Funcionario.php';

class FuncionarioDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Funcionario $obj) {
        $stmt = $this->conn->prepare("INSERT INTO funcionario (id_agencia, nome, cpf, cargo, email, telefone, data_admissao, ativo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$obj->getIdAgencia(), $obj->getNome(), $obj->getCpf(), $obj->getCargo(), $obj->getEmail(), $obj->getTelefone(), $obj->getDataAdmissao(), $obj->getAtivo()]);
    }

    public function listar() {
        $stmt = $this->conn->query("SELECT * FROM funcionario ORDER BY nome");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Funcionario($row['id_agencia'], $row['nome'], $row['cpf'], $row['cargo'], $row['email'], $row['telefone'], $row['data_admissao'], $row['ativo']);
            $obj->setId($row['id_funcionario']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Funcionario $obj) {
        $stmt = $this->conn->prepare("UPDATE funcionario SET id_agencia = ?, nome = ?, cpf = ?, cargo = ?, email = ?, telefone = ?, data_admissao = ?, ativo = ? WHERE id_funcionario = ?");
        $stmt->execute([$obj->getIdAgencia(), $obj->getNome(), $obj->getCpf(), $obj->getCargo(), $obj->getEmail(), $obj->getTelefone(), $obj->getDataAdmissao(), $obj->getAtivo(), $obj->getId()]);
    }

    public function deletar($id) {
        $stmt = $this->conn->prepare("DELETE FROM funcionario WHERE id_funcionario = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM funcionario WHERE id_funcionario = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Funcionario($row['id_agencia'], $row['nome'], $row['cpf'], $row['cargo'], $row['email'], $row['telefone'], $row['data_admissao'], $row['ativo']);
            $obj->setId($row['id_funcionario']);
            return $obj;
        }
        return null;
    }
}
