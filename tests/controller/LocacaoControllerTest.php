<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/LocacaoController.php';

class LocacaoControllerTest extends TestCase
{
    public function testSalvarComSucesso()
    {
        $daoMock = $this->createMock(LocacaoDAO::class);

        $veiculoDaoMock = $this->createMock(VeiculoDAO::class);

        $veiculo = $this->createMock(Veiculo::class);

        $veiculo->method('getStatus')
            ->willReturn('disponivel');

        $veiculoDaoMock->method('buscarPorId')
            ->willReturn($veiculo);

        $daoMock->method('veiculoPossuiLocacaoAberta')
            ->willReturn(false);

        $daoMock->expects($this->once())
            ->method('salvar');

        $veiculoDaoMock->expects($this->once())
            ->method('atualizar');

        $controller = new LocacaoController(
            $daoMock,
            $veiculoDaoMock
        );

        $controller->salvar(
            1,
            1,
            1,
            1,
            1,
            '2025-08-01',
            '2025-08-05',
            null,
            100,
            0,
            0,
            '',
            'Observação'
        );
    }

    public function testSalvarVeiculoNaoEncontrado()
    {
        $this->expectException(RuntimeException::class);

        $daoMock = $this->createMock(LocacaoDAO::class);

        $veiculoDaoMock = $this->createMock(VeiculoDAO::class);

        $veiculoDaoMock->method('buscarPorId')
            ->willReturn(null);

        $controller = new LocacaoController(
            $daoMock,
            $veiculoDaoMock
        );

        $controller->salvar(
            1,
            1,
            1,
            1,
            1,
            '2025-08-01',
            '2025-08-05',
            null,
            100,
            0,
            0,
            '',
            ''
        );
    }

    public function testSalvarVeiculoIndisponivel()
    {
        $this->expectException(RuntimeException::class);

        $daoMock = $this->createMock(LocacaoDAO::class);

        $veiculoDaoMock = $this->createMock(VeiculoDAO::class);

        $veiculo = $this->createMock(Veiculo::class);

        $veiculo->method('getStatus')
            ->willReturn('locado');

        $veiculoDaoMock->method('buscarPorId')
            ->willReturn($veiculo);

        $controller = new LocacaoController(
            $daoMock,
            $veiculoDaoMock
        );

        $controller->salvar(
            1,
            1,
            1,
            1,
            1,
            '2025-08-01',
            '2025-08-05',
            null,
            100,
            0,
            0,
            '',
            ''
        );
    }

    public function testSalvarComDatasInvalidas()
    {
        $this->expectException(RuntimeException::class);

        $daoMock = $this->createMock(LocacaoDAO::class);

        $veiculoDaoMock = $this->createMock(VeiculoDAO::class);

        $controller = new LocacaoController(
            $daoMock,
            $veiculoDaoMock
        );

        $controller->salvar(
            1,
            1,
            1,
            1,
            1,
            '2025-08-10',
            '2025-08-01',
            null,
            100,
            0,
            0,
            '',
            ''
        );
    }

    public function testListar()
    {
        $dados = ['locacao1'];

        $daoMock = $this->createMock(LocacaoDAO::class);

        $veiculoDaoMock = $this->createMock(VeiculoDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new LocacaoController(
            $daoMock,
            $veiculoDaoMock
        );

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }
}
