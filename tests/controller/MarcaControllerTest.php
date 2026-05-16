<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/MarcaController.php';
require_once __DIR__ . '/../../model/Marca.php';

class MarcaControllerTest extends TestCase
{
    public function testSalvar()
    {
        $daoMock = $this->createMock(MarcaDAO::class);

        $daoMock->method('buscarPorNome')
            ->willReturn(null);

        $daoMock->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Marca::class));

        $controller = new MarcaController($daoMock);

        $controller->salvar(
            'Toyota',
            'Japão'
        );
    }

    public function testSalvarComMarcaExistente()
    {
        $this->expectException(RuntimeException::class);

        $marcaExistente = new Marca('Toyota', 'Japão');

        $daoMock = $this->createMock(MarcaDAO::class);

        $daoMock->method('buscarPorNome')
            ->willReturn($marcaExistente);

        $controller = new MarcaController($daoMock);

        $controller->salvar(
            'Toyota',
            'Japão'
        );
    }

    public function testListar()
    {
        $dados = ['marca1', 'marca2'];

        $daoMock = $this->createMock(MarcaDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new MarcaController($daoMock);

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }

    public function testAtualizar()
    {
        $daoMock = $this->createMock(MarcaDAO::class);

        $daoMock->method('buscarPorNome')
            ->willReturn(null);

        $daoMock->expects($this->once())
            ->method('atualizar')
            ->with($this->isInstanceOf(Marca::class));

        $controller = new MarcaController($daoMock);

        $controller->atualizar(
            1,
            'Honda',
            'Japão'
        );
    }

    public function testAtualizarComNomeDuplicado()
    {
        $this->expectException(RuntimeException::class);

        $marcaExistente = new Marca('Honda', 'Japão');
        $marcaExistente->setId(2);

        $daoMock = $this->createMock(MarcaDAO::class);

        $daoMock->method('buscarPorNome')
            ->willReturn($marcaExistente);

        $controller = new MarcaController($daoMock);

        $controller->atualizar(
            1,
            'Honda',
            'Japão'
        );
    }

    public function testAtualizarMesmoRegistro()
    {
        $marcaExistente = new Marca('Honda', 'Japão');
        $marcaExistente->setId(1);

        $daoMock = $this->createMock(MarcaDAO::class);

        $daoMock->method('buscarPorNome')
            ->willReturn($marcaExistente);

        $daoMock->expects($this->once())
            ->method('atualizar');

        $controller = new MarcaController($daoMock);

        $controller->atualizar(
            1,
            'Honda',
            'Japão'
        );
    }

    public function testDeletar()
    {
        $daoMock = $this->createMock(MarcaDAO::class);

        $daoMock->expects($this->once())
            ->method('deletar')
            ->with(1);

        $controller = new MarcaController($daoMock);

        $controller->deletar(1);
    }

    public function testBuscarPorId()
    {
        $marca = new stdClass();
        $marca->id = 1;
        $marca->nome = 'Ford';

        $daoMock = $this->createMock(MarcaDAO::class);

        $daoMock->method('buscarPorId')
            ->with(1)
            ->willReturn($marca);

        $controller = new MarcaController($daoMock);

        $resultado = $controller->buscarPorId(1);

        $this->assertEquals($marca, $resultado);
    }
}
