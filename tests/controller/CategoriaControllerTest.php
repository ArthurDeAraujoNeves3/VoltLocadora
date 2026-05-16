<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/CategoriaController.php';
require_once __DIR__ . '/../../model/Categoria.php';

class CategoriaControllerTest extends TestCase
{
    public function testSalvar()
    {
        $daoMock = $this->createMock(CategoriaDAO::class);

        $daoMock->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Categoria::class));

        $controller = new CategoriaController($daoMock);

        $controller->salvar(
            'SUV',
            'Categoria de veículos SUV',
            'SUV001'
        );
    }

    public function testListar()
    {
        $dados = ['categoria1', 'categoria2'];

        $daoMock = $this->createMock(CategoriaDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new CategoriaController($daoMock);

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }

    public function testAtualizar()
    {
        $daoMock = $this->createMock(CategoriaDAO::class);

        $daoMock->expects($this->once())
            ->method('atualizar')
            ->with($this->isInstanceOf(Categoria::class));

        $controller = new CategoriaController($daoMock);

        $controller->atualizar(
            1,
            'Sedan',
            'Categoria Sedan Atualizada',
            'SED001'
        );
    }

    public function testDeletar()
    {
        $daoMock = $this->createMock(CategoriaDAO::class);

        $daoMock->expects($this->once())
            ->method('deletar')
            ->with(1);

        $controller = new CategoriaController($daoMock);

        $controller->deletar(1);
    }

    public function testBuscarPorId()
    {
        $categoria = new stdClass();
        $categoria->id = 1;
        $categoria->nome = 'SUV';

        $daoMock = $this->createMock(CategoriaDAO::class);

        $daoMock->method('buscarPorId')
            ->with(1)
            ->willReturn($categoria);

        $controller = new CategoriaController($daoMock);

        $resultado = $controller->buscarPorId(1);

        $this->assertEquals($categoria, $resultado);
    }
}
