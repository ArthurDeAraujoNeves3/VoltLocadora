<?php
require_once 'model/Locacao.php';
require_once 'dao/LocacaoDAO.php';
require_once 'model/Veiculo.php';
require_once 'dao/VeiculoDAO.php';

class LocacaoController {

    public function salvar($idCliente, $idVeiculo, $idFuncionario, $idAgenciaRetirada, $idAgenciaDevolucao, $dataRetirada, $dataDevolucaoPrevista, $dataDevolucaoReal, $valorDiaria, $totalDias, $valorTotal, $status, $observacoes) {
        $veiculoDao = new VeiculoDAO();
        $veiculo = $veiculoDao->buscarPorId($idVeiculo);

        if (!$veiculo) {
            throw new RuntimeException('Veículo não encontrado.');
        }
        if ($veiculo->getStatus() !== 'disponivel') {
            $statusLabel = ['locado' => 'Locado', 'manutencao' => 'Em Manutenção', 'inativo' => 'Inativo'];
            $label = $statusLabel[$veiculo->getStatus()] ?? $veiculo->getStatus();
            throw new RuntimeException("Veículo indisponível para locação. Status atual: {$label}.");
        }

        $dao = new LocacaoDAO();
        if ($dao->veiculoPossuiLocacaoAberta($idVeiculo)) {
            throw new RuntimeException('Este veículo já possui uma locação em aberto. Encerre a locação atual antes de criar uma nova.');
        }

        $obj = new Locacao($idCliente, $idVeiculo, $idFuncionario, $idAgenciaRetirada, $idAgenciaDevolucao, $dataRetirada, $dataDevolucaoPrevista, $dataDevolucaoReal, $valorDiaria, $totalDias, $valorTotal, $status, $observacoes);
        $dao->salvar($obj);

        $veiculo->setStatus('locado');
        $veiculoDao->atualizar($veiculo);
    }

    public function listar() {
        $dao = new LocacaoDAO();
        return $dao->listar();
    }

    public function atualizar($id, $idCliente, $idVeiculo, $idFuncionario, $idAgenciaRetirada, $idAgenciaDevolucao, $dataRetirada, $dataDevolucaoPrevista, $dataDevolucaoReal, $valorDiaria, $totalDias, $valorTotal, $status, $observacoes) {
        $dao = new LocacaoDAO();
        $locacaoAtual = $dao->buscarPorId($id);

        if ($locacaoAtual && $locacaoAtual->getStatus() === 'aberta' && in_array($status, ['encerrada', 'cancelada'])) {
            $veiculoDao = new VeiculoDAO();
            $veiculo = $veiculoDao->buscarPorId($locacaoAtual->getIdVeiculo());
            if ($veiculo) {
                $veiculo->setStatus('disponivel');
                $veiculoDao->atualizar($veiculo);
            }
        }

        $obj = new Locacao($idCliente, $idVeiculo, $idFuncionario, $idAgenciaRetirada, $idAgenciaDevolucao, $dataRetirada, $dataDevolucaoPrevista, $dataDevolucaoReal, $valorDiaria, $totalDias, $valorTotal, $status, $observacoes);
        $obj->setId($id);
        $dao->atualizar($obj);
    }

    public function deletar($id) {
        $dao = new LocacaoDAO();
        $locacao = $dao->buscarPorId($id);
        if ($locacao && $locacao->getStatus() === 'aberta') {
            $veiculoDao = new VeiculoDAO();
            $veiculo = $veiculoDao->buscarPorId($locacao->getIdVeiculo());
            if ($veiculo) {
                $veiculo->setStatus('disponivel');
                $veiculoDao->atualizar($veiculo);
            }
        }
        $dao->deletar($id);
    }

    public function buscarPorId($id) {
        $dao = new LocacaoDAO();
        return $dao->buscarPorId($id);
    }
}
