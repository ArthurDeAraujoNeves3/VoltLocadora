<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/ClienteController.php';
require_once __DIR__ . '/../../model/Cliente.php';

class ClienteControllerTest extends TestCase
{
    public function testSalvar()
    {
        $daoMock = $this->createMock(ClienteDAO::class);

        $daoMock->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Cliente::class));

        $controller = new ClienteController($daoMock);

        $controller->salvar(
            'Pedro',
            '12345678900',
            'pedro@email.com',
            '88999999999',
            '12345678901',
            '2030-01-01',
            'Rua Central',
            'Juazeiro do Norte',
            'CE',
            '63000-000',
            true
        );
    }

    public function testListar()
    {
        $dados = ['cliente1', 'cliente2'];

        $daoMock = $this->createMock(ClienteDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new ClienteController($daoMock);

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }

    public function testAtualizar()
    {
        $daoMock = $this->createMock(ClienteDAO::class);

        $daoMock->expects($this->once())
            ->method('atualizar')
            ->with($this->isInstanceOf(Cliente::class));

        $controller = new ClienteController($daoMock);

        $controller->atualizar(
            1,
            'Pedro Atualizado',
            '98765432100',
            'novo@email.com',
            '88988888888',
            '98765432199',
            '2032-10-10',
            'Nova Rua',
            'Fortaleza',
            'CE',
            '60000-000',
            false
        );
    }

    public function testDeletar()
    {
        $daoMock = $this->createMock(ClienteDAO::class);

        $daoMock->expects($this->once())
            ->method('deletar')
            ->with(1);

        $controller = new ClienteController($daoMock);

        $controller->deletar(1);
    }

    public function testBuscarPorId()
    {
        $cliente = new stdClass();
        $cliente->id = 1;
        $cliente->nome = 'Pedro';

        $daoMock = $this->createMock(ClienteDAO::class);

        $daoMock->method('buscarPorId')
            ->with(1)
            ->willReturn($cliente);

        $controller = new ClienteController($daoMock);

        $resultado = $controller->buscarPorId(1);

        $this->assertEquals($cliente, $resultado);
    }
}
