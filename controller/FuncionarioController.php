<?php
require_once 'model/Funcionario.php';
require_once 'dao/FuncionarioDAO.php';

class FuncionarioController {
    public function salvar($idAgencia, $nome, $cpf, $cargo, $email, $telefone, $dataAdmissao, $ativo) {
        $obj = new Funcionario($idAgencia, $nome, $cpf, $cargo, $email, $telefone, $dataAdmissao, $ativo);
        $dao = new FuncionarioDAO();
        $dao->salvar($obj);
    }

    public function listar() {
        $dao = new FuncionarioDAO();
        return $dao->listar();
    }

    public function atualizar($id, $idAgencia, $nome, $cpf, $cargo, $email, $telefone, $dataAdmissao, $ativo) {
        $obj = new Funcionario($idAgencia, $nome, $cpf, $cargo, $email, $telefone, $dataAdmissao, $ativo);
        $obj->setId($id);
        $dao = new FuncionarioDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id) {
        $dao = new FuncionarioDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id) {
        $dao = new FuncionarioDAO();
        return $dao->buscarPorId($id);
    }
}
