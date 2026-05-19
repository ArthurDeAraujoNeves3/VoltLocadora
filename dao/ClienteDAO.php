<?php
require_once 'config/Conexao.php';
require_once 'model/Cliente.php';

class ClienteDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Cliente $obj)
    {
        $stmt = $this->conn->prepare("INSERT INTO cliente (nome, cpf, email, telefone, cnh, vencimento_cnh, endereco, cidade, estado, cep, ativo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$obj->getNome(), $obj->getCpf(), $obj->getEmail(), $obj->getTelefone(), $obj->getCnh(), $obj->getVencimentoCnh(), $obj->getEndereco(), $obj->getCidade(), $obj->getEstado(), $obj->getCep(), $obj->getAtivo()]);
    }

    public function listar()
    {
        $stmt = $this->conn->query("SELECT * FROM cliente ORDER BY nome");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Cliente($row['nome'], $row['cpf'], $row['email'], $row['telefone'], $row['cnh'], $row['vencimento_cnh'], $row['endereco'], $row['cidade'], $row['estado'], $row['cep'], $row['ativo']);
            $obj->setId($row['id_cliente']);
            $obj->setDataCadastro($row['data_cadastro']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Cliente $obj)
    {
        $stmt = $this->conn->prepare("UPDATE cliente SET nome = ?, cpf = ?, email = ?, telefone = ?, cnh = ?, vencimento_cnh = ?, endereco = ?, cidade = ?, estado = ?, cep = ?, ativo = ? WHERE id_cliente = ?");
        $stmt->execute([$obj->getNome(), $obj->getCpf(), $obj->getEmail(), $obj->getTelefone(), $obj->getCnh(), $obj->getVencimentoCnh(), $obj->getEndereco(), $obj->getCidade(), $obj->getEstado(), $obj->getCep(), $obj->getAtivo(), $obj->getId()]);
    }

    public function deletar($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM cliente WHERE id_cliente = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM cliente WHERE id_cliente = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Cliente($row['nome'], $row['cpf'], $row['email'], $row['telefone'], $row['cnh'], $row['vencimento_cnh'], $row['endereco'], $row['cidade'], $row['estado'], $row['cep'], $row['ativo']);
            $obj->setId($row['id_cliente']);
            $obj->setDataCadastro($row['data_cadastro']);
            return $obj;
        }
        return null;
    }
}
