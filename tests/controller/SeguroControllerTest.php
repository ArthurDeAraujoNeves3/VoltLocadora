<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/SeguroController.php';
require_once __DIR__ . '/../../model/Seguro.php';

class SeguroControllerTest extends TestCase
{
    public function testSalvar()
    {
        $daoMock = $this->createMock(SeguroDAO::class);

        $daoMock->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Seguro::class));

        $controller = new SeguroController($daoMock);

        $controller->salvar(
            1,
            'Completo',
            'Cobertura total contra colisão e roubo',
            2500.00,
            79.90,
            'APL123456789'
        );
    }

    public function testListar()
    {
        $dados = ['seguro1', 'seguro2'];

        $daoMock = $this->createMock(SeguroDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new SeguroController($daoMock);

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }

    public function testAtualizar()
    {
        $daoMock = $this->createMock(SeguroDAO::class);

        $daoMock->expects($this->once())
            ->method('atualizar')
            ->with($this->isInstanceOf(Seguro::class));

        $controller = new SeguroController($daoMock);

        $controller->atualizar(
            1,
            2,
            'Parcial',
            'Cobertura parcial para terceiros',
            1800.00,
            49.90,
            'APL987654321'
        );
    }

    public function testDeletar()
    {
        $daoMock = $this->createMock(SeguroDAO::class);

        $daoMock->expects($this->once())
            ->method('deletar')
            ->with(1);

        $controller = new SeguroController($daoMock);

        $controller->deletar(1);
    }

    public function testBuscarPorId()
    {
        $seguro = new stdClass();
        $seguro->id = 1;
        $seguro->tipo = 'Completo';

        $daoMock = $this->createMock(SeguroDAO::class);

        $daoMock->method('buscarPorId')
            ->with(1)
            ->willReturn($seguro);

        $controller = new SeguroController($daoMock);

        $resultado = $controller->buscarPorId(1);

        $this->assertEquals($seguro, $resultado);
    }
}
