<?php
require_once 'config/Conexao.php';
require_once 'model/Categoria.php';

class CategoriaDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Categoria $obj)
    {
        $stmt = $this->conn->prepare("INSERT INTO categoria (nome, descricao, codigo) VALUES (?, ?, ?)");
        $stmt->execute([$obj->getNome(), $obj->getDescricao(), $obj->getCodigo()]);
    }

    public function listar()
    {
        $stmt = $this->conn->query("SELECT * FROM categoria ORDER BY nome");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Categoria($row['nome'], $row['descricao'], $row['codigo']);
            $obj->setId($row['id_categoria']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Categoria $obj)
    {
        $stmt = $this->conn->prepare("UPDATE categoria SET nome = ?, descricao = ?, codigo = ? WHERE id_categoria = ?");
        $stmt->execute([$obj->getNome(), $obj->getDescricao(), $obj->getCodigo(), $obj->getId()]);
    }

    public function deletar($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM categoria WHERE id_categoria = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM categoria WHERE id_categoria = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Categoria($row['nome'], $row['descricao'], $row['codigo']);
            $obj->setId($row['id_categoria']);
            return $obj;
        }
        return null;
    }
}
