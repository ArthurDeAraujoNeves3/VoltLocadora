<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/VeiculoController.php';
require_once __DIR__ . '/../../model/Veiculo.php';

class VeiculoControllerTest extends TestCase
{
    public function testSalvar()
    {
        $daoMock = $this->createMock(VeiculoDAO::class);

        $locacaoDaoMock = $this->createMock(LocacaoDAO::class);

        $daoMock->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Veiculo::class));

        $controller = new VeiculoController(
            $daoMock,
            $locacaoDaoMock
        );

        $controller->salvar(
            1,
            1,
            'Corolla',
            2023,
            2024,
            'ABC1234',
            'CHASSI123',
            'Preto',
            250.00,
            'disponivel',
            15000,
            'Flex',
            4,
            5
        );
    }

    public function testListar()
    {
        $dados = ['veiculo1', 'veiculo2'];

        $daoMock = $this->createMock(VeiculoDAO::class);

        $locacaoDaoMock = $this->createMock(LocacaoDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new VeiculoController(
            $daoMock,
            $locacaoDaoMock
        );

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }

    public function testAtualizar()
    {
        $daoMock = $this->createMock(VeiculoDAO::class);

        $locacaoDaoMock = $this->createMock(LocacaoDAO::class);

        $locacaoDaoMock->method('veiculoPossuiLocacaoAberta')
            ->willReturn(false);

        $daoMock->expects($this->once())
            ->method('atualizar')
            ->with($this->isInstanceOf(Veiculo::class));

        $controller = new VeiculoController(
            $daoMock,
            $locacaoDaoMock
        );

        $controller->atualizar(
            1,
            1,
            1,
            'Civic',
            2022,
            2023,
            'XYZ9999',
            'CHASSI999',
            'Branco',
            300,
            'disponivel',
            22000,
            'Gasolina',
            4,
            5
        );
    }

    public function testAtualizarComLocacaoAberta()
    {
        $this->expectException(RuntimeException::class);

        $daoMock = $this->createMock(VeiculoDAO::class);

        $locacaoDaoMock = $this->createMock(LocacaoDAO::class);

        $locacaoDaoMock->method('veiculoPossuiLocacaoAberta')
            ->willReturn(true);

        $controller = new VeiculoController(
            $daoMock,
            $locacaoDaoMock
        );

        $controller->atualizar(
            1,
            1,
            1,
            'Civic',
            2022,
            2023,
            'XYZ9999',
            'CHASSI999',
            'Branco',
            300,
            'manutencao',
            22000,
            'Gasolina',
            4,
            5
        );
    }

    public function testDeletar()
    {
        $daoMock = $this->createMock(VeiculoDAO::class);

        $locacaoDaoMock = $this->createMock(LocacaoDAO::class);

        $locacaoDaoMock->method('veiculoPossuiLocacaoAberta')
            ->willReturn(false);

        $daoMock->expects($this->once())
            ->method('deletar')
            ->with(1);

        $controller = new VeiculoController(
            $daoMock,
            $locacaoDaoMock
        );

        $controller->deletar(1);
    }

    public function testDeletarComLocacaoAberta()
    {
        $this->expectException(RuntimeException::class);

        $daoMock = $this->createMock(VeiculoDAO::class);

        $locacaoDaoMock = $this->createMock(LocacaoDAO::class);

        $locacaoDaoMock->method('veiculoPossuiLocacaoAberta')
            ->willReturn(true);

        $controller = new VeiculoController(
            $daoMock,
            $locacaoDaoMock
        );

        $controller->deletar(1);
    }

    public function testBuscarPorId()
    {
        $veiculo = new stdClass();
        $veiculo->id = 1;
        $veiculo->modelo = 'Corolla';

        $daoMock = $this->createMock(VeiculoDAO::class);

        $locacaoDaoMock = $this->createMock(LocacaoDAO::class);

        $daoMock->method('buscarPorId')
            ->with(1)
            ->willReturn($veiculo);

        $controller = new VeiculoController(
            $daoMock,
            $locacaoDaoMock
        );

        $resultado = $controller->buscarPorId(1);

        $this->assertEquals($veiculo, $resultado);
    }
}
