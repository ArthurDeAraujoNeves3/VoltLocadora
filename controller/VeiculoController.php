<?php

require_once 'model/Veiculo.php';
require_once 'dao/VeiculoDAO.php';
require_once 'dao/LocacaoDAO.php';

class VeiculoController
{

    private $dao;
    private $locacaoDao;

    public function __construct($dao = null, $locacaoDao = null)
    {
        $this->dao = $dao ?? new VeiculoDAO();
        $this->locacaoDao = $locacaoDao ?? new LocacaoDAO();
    }

    public function salvar(
        $idCategoria,
        $idMarca,
        $modelo,
        $anoFabricacao,
        $anoModelo,
        $placa,
        $chassi,
        $cor,
        $valorDiaria,
        $status,
        $quilometragem,
        $combustivel,
        $numPortas,
        $capacidadePassageiros
    ) {

        $obj = new Veiculo(
            $idCategoria,
            $idMarca,
            $modelo,
            $anoFabricacao,
            $anoModelo,
            $placa,
            $chassi,
            $cor,
            $valorDiaria,
            $status,
            $quilometragem,
            $combustivel,
            $numPortas,
            $capacidadePassageiros
        );

        $this->dao->salvar($obj);
    }

    public function listar()
    {
        return $this->dao->listar();
    }

    public function atualizar(
        $id,
        $idCategoria,
        $idMarca,
        $modelo,
        $anoFabricacao,
        $anoModelo,
        $placa,
        $chassi,
        $cor,
        $valorDiaria,
        $status,
        $quilometragem,
        $combustivel,
        $numPortas,
        $capacidadePassageiros
    ) {

        if (
            $this->locacaoDao->veiculoPossuiLocacaoAberta($id) &&
            $status !== 'locado'
        ) {

            throw new RuntimeException(
                'Este veículo possui uma locação em aberto e não pode ter o status alterado.'
            );
        }

        $obj = new Veiculo(
            $idCategoria,
            $idMarca,
            $modelo,
            $anoFabricacao,
            $anoModelo,
            $placa,
            $chassi,
            $cor,
            $valorDiaria,
            $status,
            $quilometragem,
            $combustivel,
            $numPortas,
            $capacidadePassageiros
        );

        $obj->setId($id);

        $this->dao->atualizar($obj);
    }

    public function deletar($id)
    {

        if ($this->locacaoDao->veiculoPossuiLocacaoAberta($id)) {

            throw new RuntimeException(
                'Não é possível excluir: este veículo possui uma locação em aberto.'
            );
        }

        $this->dao->deletar($id);
    }

    public function buscarPorId($id)
    {
        return $this->dao->buscarPorId($id);
    }
}
