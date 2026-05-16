<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/FuncionarioController.php';
require_once __DIR__ . '/../../model/Funcionario.php';

class FuncionarioControllerTest extends TestCase
{
    public function testSalvar()
    {
        $daoMock = $this->createMock(FuncionarioDAO::class);

        $daoMock->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Funcionario::class));

        $controller = new FuncionarioController($daoMock);

        $controller->salvar(
            1,
            'João Silva',
            '12345678900',
            'Gerente',
            'joao@email.com',
            '88999999999',
            '2025-01-01',
            true
        );
    }

    public function testListar()
    {
        $dados = ['funcionario1', 'funcionario2'];

        $daoMock = $this->createMock(FuncionarioDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new FuncionarioController($daoMock);

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }

    public function testAtualizar()
    {
        $daoMock = $this->createMock(FuncionarioDAO::class);

        $daoMock->expects($this->once())
            ->method('atualizar')
            ->with($this->isInstanceOf(Funcionario::class));

        $controller = new FuncionarioController($daoMock);

        $controller->atualizar(
            1,
            2,
            'Maria Souza',
            '98765432100',
            'Atendente',
            'maria@email.com',
            '88988888888',
            '2024-05-10',
            false
        );
    }

    public function testDeletar()
    {
        $daoMock = $this->createMock(FuncionarioDAO::class);

        $daoMock->expects($this->once())
            ->method('deletar')
            ->with(1);

        $controller = new FuncionarioController($daoMock);

        $controller->deletar(1);
    }

    public function testBuscarPorId()
    {
        $funcionario = new stdClass();
        $funcionario->id = 1;
        $funcionario->nome = 'Carlos';

        $daoMock = $this->createMock(FuncionarioDAO::class);

        $daoMock->method('buscarPorId')
            ->with(1)
            ->willReturn($funcionario);

        $controller = new FuncionarioController($daoMock);

        $resultado = $controller->buscarPorId(1);

        $this->assertEquals($funcionario, $resultado);
    }
}
