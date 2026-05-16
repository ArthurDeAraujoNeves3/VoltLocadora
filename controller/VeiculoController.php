<?php
require_once 'model/Veiculo.php';
require_once 'dao/VeiculoDAO.php';

class VeiculoController {
    public function salvar($idCategoria, $idMarca, $modelo, $anoFabricacao, $anoModelo, $placa, $chassi, $cor, $valorDiaria, $status, $quilometragem, $combustivel, $numPortas, $capacidadePassageiros) {
        $obj = new Veiculo($idCategoria, $idMarca, $modelo, $anoFabricacao, $anoModelo, $placa, $chassi, $cor, $valorDiaria, $status, $quilometragem, $combustivel, $numPortas, $capacidadePassageiros);
        $dao = new VeiculoDAO();
        $dao->salvar($obj);
    }

    public function listar() {
        $dao = new VeiculoDAO();
        return $dao->listar();
    }

    public function atualizar($id, $idCategoria, $idMarca, $modelo, $anoFabricacao, $anoModelo, $placa, $chassi, $cor, $valorDiaria, $status, $quilometragem, $combustivel, $numPortas, $capacidadePassageiros) {
        $obj = new Veiculo($idCategoria, $idMarca, $modelo, $anoFabricacao, $anoModelo, $placa, $chassi, $cor, $valorDiaria, $status, $quilometragem, $combustivel, $numPortas, $capacidadePassageiros);
        $obj->setId($id);
        $dao = new VeiculoDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id) {
        $dao = new VeiculoDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id) {
        $dao = new VeiculoDAO();
        return $dao->buscarPorId($id);
    }
}
