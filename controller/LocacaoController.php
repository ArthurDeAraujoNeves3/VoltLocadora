<?php
require_once 'model/Locacao.php';
require_once 'dao/LocacaoDAO.php';
require_once 'model/Veiculo.php';
require_once 'dao/VeiculoDAO.php';

class LocacaoController {

    public function salvar($idCliente, $idVeiculo, $idFuncionario, $idAgenciaRetirada, $idAgenciaDevolucao, $dataRetirada, $dataDevolucaoPrevista, $dataDevolucaoReal, $valorDiaria, $totalDias, $valorTotal, $status, $observacoes) {
        $this->validarDatas($dataRetirada, $dataDevolucaoPrevista);

        $veiculoDao = new VeiculoDAO();
        $veiculo = $veiculoDao->buscarPorId($idVeiculo);
        if (!$veiculo) {
            throw new RuntimeException('Veículo não encontrado.');
        }
        if ($veiculo->getStatus() !== 'disponivel') {
            $labels = ['locado' => 'Locado', 'manutencao' => 'Em Manutenção', 'inativo' => 'Inativo'];
            throw new RuntimeException('Veículo indisponível. Status atual: ' . ($labels[$veiculo->getStatus()] ?? $veiculo->getStatus()) . '.');
        }

        $dao = new LocacaoDAO();
        if ($dao->veiculoPossuiLocacaoAberta($idVeiculo)) {
            throw new RuntimeException('Este veículo já possui uma locação em aberto. Encerre-a antes de criar uma nova.');
        }

        // Novas locações só podem iniciar como 'aberta'
        $status = 'aberta';

        // Servidor recalcula — ignora valores manipulados via POST
        [$totalDias, $valorTotal] = $this->calcularTotais($dataRetirada, $dataDevolucaoPrevista, $valorDiaria);

        $obj = new Locacao($idCliente, $idVeiculo, $idFuncionario, $idAgenciaRetirada, $idAgenciaDevolucao, $dataRetirada, $dataDevolucaoPrevista, $dataDevolucaoReal ?: null, $valorDiaria, $totalDias, $valorTotal, $status, $observacoes);
        $dao->salvar($obj);

        $veiculo->setStatus('locado');
        $veiculoDao->atualizar($veiculo);
    }

    public function listar() {
        $dao = new LocacaoDAO();
        return $dao->listar();
    }

    public function atualizar($id, $idCliente, $idVeiculo, $idFuncionario, $idAgenciaRetirada, $idAgenciaDevolucao, $dataRetirada, $dataDevolucaoPrevista, $dataDevolucaoReal, $valorDiaria, $totalDias, $valorTotal, $status, $observacoes) {
        $this->validarDatas($dataRetirada, $dataDevolucaoPrevista);

        $dao        = new LocacaoDAO();
        $veiculoDao = new VeiculoDAO();
        $atual      = $dao->buscarPorId($id);

        if ($atual && $atual->getStatus() === 'aberta') {
            $idVeiculoAntigo = $atual->getIdVeiculo();
            $veiculoMudou    = ($idVeiculoAntigo != $idVeiculo);

            // Libera o veículo anterior (qualquer que seja o caso)
            $veiculoAntigo = $veiculoDao->buscarPorId($idVeiculoAntigo);
            if ($veiculoAntigo && $veiculoAntigo->getStatus() === 'locado') {
                $veiculoAntigo->setStatus('disponivel');
                $veiculoDao->atualizar($veiculoAntigo);
            }

            if ($status === 'aberta') {
                if ($veiculoMudou) {
                    // Novo veículo: valida disponibilidade
                    $novoVeiculo = $veiculoDao->buscarPorId($idVeiculo);
                    if (!$novoVeiculo || $novoVeiculo->getStatus() !== 'disponivel') {
                        // Rollback: re-bloqueia o veículo anterior
                        if ($veiculoAntigo) { $veiculoAntigo->setStatus('locado'); $veiculoDao->atualizar($veiculoAntigo); }
                        throw new RuntimeException('O novo veículo selecionado não está disponível.');
                    }
                    if ($dao->veiculoPossuiLocacaoAbertaExceto($idVeiculo, $id)) {
                        if ($veiculoAntigo) { $veiculoAntigo->setStatus('locado'); $veiculoDao->atualizar($veiculoAntigo); }
                        throw new RuntimeException('O novo veículo já possui outra locação em aberto.');
                    }
                    $novoVeiculo->setStatus('locado');
                    $veiculoDao->atualizar($novoVeiculo);
                } else {
                    // Mesmo veículo, locação continua aberta — re-bloqueia
                    if ($veiculoAntigo) { $veiculoAntigo->setStatus('locado'); $veiculoDao->atualizar($veiculoAntigo); }
                }
            }
            // Se status → 'encerrada'/'cancelada': veículo já foi liberado acima
        }

        // Recalcula totais no servidor
        [$totalDias, $valorTotal] = $this->calcularTotais($dataRetirada, $dataDevolucaoPrevista, $valorDiaria);

        $obj = new Locacao($idCliente, $idVeiculo, $idFuncionario, $idAgenciaRetirada, $idAgenciaDevolucao, $dataRetirada, $dataDevolucaoPrevista, $dataDevolucaoReal ?: null, $valorDiaria, $totalDias, $valorTotal, $status, $observacoes);
        $obj->setId($id);
        $dao->atualizar($obj);
    }

    public function deletar($id) {
        $dao = new LocacaoDAO();
        $locacao = $dao->buscarPorId($id);
        if ($locacao && $locacao->getStatus() === 'aberta') {
            $veiculoDao = new VeiculoDAO();
            $veiculo = $veiculoDao->buscarPorId($locacao->getIdVeiculo());
            if ($veiculo) { $veiculo->setStatus('disponivel'); $veiculoDao->atualizar($veiculo); }
        }
        $dao->deletar($id);
    }

    public function buscarPorId($id) {
        $dao = new LocacaoDAO();
        return $dao->buscarPorId($id);
    }

    private function validarDatas($dataRetirada, $dataDevolucaoPrevista) {
        if ($dataRetirada && $dataDevolucaoPrevista && $dataDevolucaoPrevista < $dataRetirada) {
            throw new RuntimeException('A data de devolução prevista não pode ser anterior à data de retirada.');
        }
    }

    private function calcularTotais($dataRetirada, $dataDevolucaoPrevista, $valorDiaria) {
        if ($dataRetirada && $dataDevolucaoPrevista) {
            $diff      = (strtotime($dataDevolucaoPrevista) - strtotime($dataRetirada)) / 86400;
            $totalDias = max(1, (int)round($diff));
        } else {
            $totalDias = 1;
        }
        $valorTotal = round($totalDias * (float)$valorDiaria, 2);
        return [$totalDias, $valorTotal];
    }
}
