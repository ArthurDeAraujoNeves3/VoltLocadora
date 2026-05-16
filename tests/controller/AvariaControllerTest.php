<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/AvariaController.php';
require_once __DIR__ . '/../../model/Avaria.php';

class AvariaControllerTest extends TestCase
{
    public function testSalvar()
    {
        $daoMock = $this->createMock(AvariaDAO::class);

        $daoMock->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Avaria::class));

        $controller = new AvariaController($daoMock);

        $controller->salvar(
            1,
            2,
            'Parachoque quebrado',
            1500.00,
            'PENDENTE',
            true
        );
    }

    public function testListar()
    {
        $dados = ['avaria1', 'avaria2'];

        $daoMock = $this->createMock(AvariaDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new AvariaController($daoMock);

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }

    public function testAtualizar()
    {
        $daoMock = $this->createMock(AvariaDAO::class);

        $daoMock->expects($this->once())
            ->method('atualizar')
            ->with($this->isInstanceOf(Avaria::class));

        $controller = new AvariaController($daoMock);

        $controller->atualizar(
            1,
            10,
            20,
            'Retrovisor quebrado',
            800,
            'CONCLUIDO',
            false
        );
    }

    public function testDeletar()
    {
        $daoMock = $this->createMock(AvariaDAO::class);

        $daoMock->expects($this->once())
            ->method('deletar')
            ->with(1);

        $controller = new AvariaController($daoMock);

        $controller->deletar(1);
    }

    public function testBuscarPorId()
    {
        $avaria = new stdClass();
        $avaria->id = 1;
        $avaria->descricao = 'Vidro quebrado';

        $daoMock = $this->createMock(AvariaDAO::class);

        $daoMock->method('buscarPorId')
            ->with(1)
            ->willReturn($avaria);

        $controller = new AvariaController($daoMock);

        $resultado = $controller->buscarPorId(1);

        $this->assertEquals($avaria, $resultado);
    }
}
