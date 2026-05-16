<?php

require_once 'model/Locacao.php';
require_once 'dao/LocacaoDAO.php';
require_once 'model/Veiculo.php';
require_once 'dao/VeiculoDAO.php';

class LocacaoController
{

    private $dao;
    private $veiculoDao;

    public function __construct($dao = null, $veiculoDao = null)
    {
        $this->dao = $dao ?? new LocacaoDAO();
        $this->veiculoDao = $veiculoDao ?? new VeiculoDAO();
    }

    public function salvar(
        $idCliente,
        $idVeiculo,
        $idFuncionario,
        $idAgenciaRetirada,
        $idAgenciaDevolucao,
        $dataRetirada,
        $dataDevolucaoPrevista,
        $dataDevolucaoReal,
        $valorDiaria,
        $totalDias,
        $valorTotal,
        $status,
        $observacoes
    ) {

        $this->validarDatas($dataRetirada, $dataDevolucaoPrevista);

        $veiculo = $this->veiculoDao->buscarPorId($idVeiculo);

        if (!$veiculo) {
            throw new RuntimeException('Veículo não encontrado.');
        }

        if ($veiculo->getStatus() !== 'disponivel') {
            throw new RuntimeException('Veículo indisponível.');
        }

        if ($this->dao->veiculoPossuiLocacaoAberta($idVeiculo)) {
            throw new RuntimeException(
                'Este veículo já possui uma locação em aberto.'
            );
        }

        $status = 'aberta';

        [$totalDias, $valorTotal] = $this->calcularTotais(
            $dataRetirada,
            $dataDevolucaoPrevista,
            $valorDiaria
        );

        $obj = new Locacao(
            $idCliente,
            $idVeiculo,
            $idFuncionario,
            $idAgenciaRetirada,
            $idAgenciaDevolucao,
            $dataRetirada,
            $dataDevolucaoPrevista,
            $dataDevolucaoReal ?: null,
            $valorDiaria,
            $totalDias,
            $valorTotal,
            $status,
            $observacoes
        );

        $this->dao->salvar($obj);

        $veiculo->setStatus('locado');

        $this->veiculoDao->atualizar($veiculo);
    }

    public function listar()
    {
        return $this->dao->listar();
    }

    public function buscarPorId($id)
    {
        return $this->dao->buscarPorId($id);
    }

    public function deletar($id)
    {

        $locacao = $this->dao->buscarPorId($id);

        if ($locacao && $locacao->getStatus() === 'aberta') {

            $veiculo = $this->veiculoDao->buscarPorId(
                $locacao->getIdVeiculo()
            );

            if ($veiculo) {
                $veiculo->setStatus('disponivel');

                $this->veiculoDao->atualizar($veiculo);
            }
        }

        $this->dao->deletar($id);
    }

    private function validarDatas($dataRetirada, $dataDevolucaoPrevista)
    {

        if (
            $dataRetirada &&
            $dataDevolucaoPrevista &&
            $dataDevolucaoPrevista < $dataRetirada
        ) {
            throw new RuntimeException(
                'A data de devolução prevista não pode ser anterior à data de retirada.'
            );
        }
    }

    private function calcularTotais(
        $dataRetirada,
        $dataDevolucaoPrevista,
        $valorDiaria
    ) {

        if ($dataRetirada && $dataDevolucaoPrevista) {

            $diff = (
                strtotime($dataDevolucaoPrevista) -
                strtotime($dataRetirada)
            ) / 86400;

            $totalDias = max(1, (int)round($diff));
        } else {

            $totalDias = 1;
        }

        $valorTotal = round(
            $totalDias * (float)$valorDiaria,
            2
        );

        return [$totalDias, $valorTotal];
    }
}
