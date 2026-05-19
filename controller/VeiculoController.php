<?php
require_once 'model/Veiculo.php';
require_once 'dao/VeiculoDAO.php';
require_once 'dao/LocacaoDAO.php';

class VeiculoController
{
    public function salvar($idCategoria, $idMarca, $modelo, $anoFabricacao, $anoModelo, $placa, $chassi, $cor, $valorDiaria, $status, $quilometragem, $combustivel, $numPortas, $capacidadePassageiros)
    {
        $obj = new Veiculo($idCategoria, $idMarca, $modelo, $anoFabricacao, $anoModelo, $placa, $chassi, $cor, $valorDiaria, $status, $quilometragem, $combustivel, $numPortas, $capacidadePassageiros);
        $dao = new VeiculoDAO();
        $dao->salvar($obj);
    }

    public function listar()
    {
        $dao = new VeiculoDAO();
        return $dao->listar();
    }

    public function atualizar($id, $idCategoria, $idMarca, $modelo, $anoFabricacao, $anoModelo, $placa, $chassi, $cor, $valorDiaria, $status, $quilometragem, $combustivel, $numPortas, $capacidadePassageiros)
    {
        // Impede alterar status de veículo com locação aberta para algo diferente de 'locado'
        $locacaoDao = new LocacaoDAO();
        if ($locacaoDao->veiculoPossuiLocacaoAberta($id) && $status !== 'locado') {
            throw new RuntimeException('Este veículo possui uma locação em aberto e não pode ter o status alterado. Encerre ou cancele a locação primeiro.');
        }

        $obj = new Veiculo($idCategoria, $idMarca, $modelo, $anoFabricacao, $anoModelo, $placa, $chassi, $cor, $valorDiaria, $status, $quilometragem, $combustivel, $numPortas, $capacidadePassageiros);
        $obj->setId($id);
        $dao = new VeiculoDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id)
    {
        $locacaoDao = new LocacaoDAO();
        if ($locacaoDao->veiculoPossuiLocacaoAberta($id)) {
            throw new RuntimeException('Não é possível excluir: este veículo possui uma locação em aberto.');
        }
        $dao = new VeiculoDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id)
    {
        $dao = new VeiculoDAO();
        return $dao->buscarPorId($id);
    }
}
