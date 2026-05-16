<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/ManutencaoController.php';
require_once __DIR__ . '/../../model/Manutencao.php';

class ManutencaoControllerTest extends TestCase
{
    public function testSalvar()
    {
        $daoMock = $this->createMock(ManutencaoDAO::class);

        $daoMock->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Manutencao::class));

        $controller = new ManutencaoController($daoMock);

        $controller->salvar(
            1,
            2,
            'Preventiva',
            'Troca de óleo e filtros',
            350.00,
            '2025-08-01',
            '2025-08-03',
            'em_andamento',
            45000
        );
    }

    public function testListar()
    {
        $dados = ['manutencao1', 'manutencao2'];

        $daoMock = $this->createMock(ManutencaoDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new ManutencaoController($daoMock);

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }

    public function testAtualizar()
    {
        $daoMock = $this->createMock(ManutencaoDAO::class);

        $daoMock->expects($this->once())
            ->method('atualizar')
            ->with($this->isInstanceOf(Manutencao::class));

        $controller = new ManutencaoController($daoMock);

        $controller->atualizar(
            1,
            3,
            4,
            'Corretiva',
            'Substituição de pneus',
            1200.00,
            '2025-08-10',
            '2025-08-15',
            'finalizada',
            50000
        );
    }

    public function testDeletar()
    {
        $daoMock = $this->createMock(ManutencaoDAO::class);

        $daoMock->expects($this->once())
            ->method('deletar')
            ->with(1);

        $controller = new ManutencaoController($daoMock);

        $controller->deletar(1);
    }

    public function testBuscarPorId()
    {
        $manutencao = new stdClass();
        $manutencao->id = 1;
        $manutencao->tipo = 'Preventiva';

        $daoMock = $this->createMock(ManutencaoDAO::class);

        $daoMock->method('buscarPorId')
            ->with(1)
            ->willReturn($manutencao);

        $controller = new ManutencaoController($daoMock);

        $resultado = $controller->buscarPorId(1);

        $this->assertEquals($manutencao, $resultado);
    }
}
