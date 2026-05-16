<?php
require_once 'model/Agencia.php';
require_once 'dao/AgenciaDAO.php';

class AgenciaController {
    public function salvar($nome, $cnpj, $endereco, $cidade, $estado, $cep, $telefone, $email, $matriz) {
        $obj = new Agencia($nome, $cnpj, $endereco, $cidade, $estado, $cep, $telefone, $email, $matriz);
        $dao = new AgenciaDAO();
        $dao->salvar($obj);
    }

    public function listar() {
        $dao = new AgenciaDAO();
        return $dao->listar();
    }

    public function atualizar($id, $nome, $cnpj, $endereco, $cidade, $estado, $cep, $telefone, $email, $matriz) {
        $obj = new Agencia($nome, $cnpj, $endereco, $cidade, $estado, $cep, $telefone, $email, $matriz);
        $obj->setId($id);
        $dao = new AgenciaDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id) {
        $dao = new AgenciaDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id) {
        $dao = new AgenciaDAO();
        return $dao->buscarPorId($id);
    }
}
