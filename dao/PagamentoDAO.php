<?php
require_once 'config/Conexao.php';
require_once 'model/Pagamento.php';

class PagamentoDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Pagamento $obj)
    {
        $stmt = $this->conn->prepare("INSERT INTO pagamento (id_locacao, valor, forma_pagamento, status, codigo_transacao, data_pagamento, parcelas) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$obj->getIdLocacao(), $obj->getValor(), $obj->getFormaPagamento(), $obj->getStatus(), $obj->getCodigoTransacao(), $obj->getDataPagamento() ?: date('Y-m-d H:i:s'), $obj->getParcelas()]);
    }

    public function listar()
    {
        $stmt = $this->conn->query("SELECT * FROM pagamento ORDER BY data_pagamento DESC");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Pagamento($row['id_locacao'], $row['valor'], $row['forma_pagamento'], $row['status'], $row['codigo_transacao'], $row['data_pagamento'], $row['parcelas']);
            $obj->setId($row['id_pagamento']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(Pagamento $obj)
    {
        $stmt = $this->conn->prepare("UPDATE pagamento SET id_locacao = ?, valor = ?, forma_pagamento = ?, status = ?, codigo_transacao = ?, parcelas = ? WHERE id_pagamento = ?");
        $stmt->execute([$obj->getIdLocacao(), $obj->getValor(), $obj->getFormaPagamento(), $obj->getStatus(), $obj->getCodigoTransacao(), $obj->getParcelas(), $obj->getId()]);
    }

    public function deletar($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM pagamento WHERE id_pagamento = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM pagamento WHERE id_pagamento = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new Pagamento($row['id_locacao'], $row['valor'], $row['forma_pagamento'], $row['status'], $row['codigo_transacao'], $row['data_pagamento'], $row['parcelas']);
            $obj->setId($row['id_pagamento']);
            return $obj;
        }
        return null;
    }
}
