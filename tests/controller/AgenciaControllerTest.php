<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/AgenciaController.php';
require_once __DIR__ . '/../../model/Agencia.php';

class AgenciaControllerTest extends TestCase
{
    public function testSalvar()
    {
        $daoMock = $this->createMock(AgenciaDAO::class);

        $daoMock->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Agencia::class));

        $controller = new AgenciaController($daoMock);

        $controller->salvar(
            'Agencia Central',
            '123456789',
            'Rua A',
            'São Paulo',
            'SP',
            '00000-000',
            '11999999999',
            'teste@email.com',
            true
        );
    }

    public function testListar()
    {
        $dados = ['agencia1', 'agencia2'];

        $daoMock = $this->createMock(AgenciaDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new AgenciaController($daoMock);

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }

    public function testDeletar()
    {
        $daoMock = $this->createMock(AgenciaDAO::class);

        $daoMock->expects($this->once())
            ->method('deletar')
            ->with(1);

        $controller = new AgenciaController($daoMock);

        $controller->deletar(1);
    }

    public function testBuscarPorId()
    {
        $agencia = new stdClass();
        $agencia->id = 1;

        $daoMock = $this->createMock(AgenciaDAO::class);

        $daoMock->method('buscarPorId')
            ->with(1)
            ->willReturn($agencia);

        $controller = new AgenciaController($daoMock);

        $resultado = $controller->buscarPorId(1);

        $this->assertEquals($agencia, $resultado);
    }

    public function testAtualizar()
    {
        $daoMock = $this->createMock(AgenciaDAO::class);

        $daoMock->expects($this->once())
            ->method('atualizar')
            ->with($this->isInstanceOf(Agencia::class));

        $controller = new AgenciaController($daoMock);

        $controller->atualizar(
            1,
            'Nova Agencia',
            '123456789',
            'Rua B',
            'Rio',
            'RJ',
            '11111-111',
            '21999999999',
            'novo@email.com',
            false
        );
    }
}
