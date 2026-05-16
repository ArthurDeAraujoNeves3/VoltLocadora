<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/PagamentoController.php';
require_once __DIR__ . '/../../model/Pagamento.php';

class PagamentoControllerTest extends TestCase
{
    public function testSalvar()
    {
        $daoMock = $this->createMock(PagamentoDAO::class);

        $daoMock->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Pagamento::class));

        $controller = new PagamentoController($daoMock);

        $controller->salvar(
            1,
            1500.00,
            'cartao_credito',
            'pago',
            'TXN123456',
            '2025-08-15',
            3
        );
    }

    public function testListar()
    {
        $dados = ['pagamento1', 'pagamento2'];

        $daoMock = $this->createMock(PagamentoDAO::class);

        $daoMock->method('listar')
            ->willReturn($dados);

        $controller = new PagamentoController($daoMock);

        $resultado = $controller->listar();

        $this->assertEquals($dados, $resultado);
    }

    public function testAtualizar()
    {
        $daoMock = $this->createMock(PagamentoDAO::class);

        $daoMock->expects($this->once())
            ->method('atualizar')
            ->with($this->isInstanceOf(Pagamento::class));

        $controller = new PagamentoController($daoMock);

        $controller->atualizar(
            1,
            2,
            2200.50,
            'pix',
            'pendente',
            'PIX999888',
            '2025-09-01',
            1
        );
    }

    public function testDeletar()
    {
        $daoMock = $this->createMock(PagamentoDAO::class);

        $daoMock->expects($this->once())
            ->method('deletar')
            ->with(1);

        $controller = new PagamentoController($daoMock);

        $controller->deletar(1);
    }

    public function testBuscarPorId()
    {
        $pagamento = new stdClass();
        $pagamento->id = 1;
        $pagamento->valor = 999.99;

        $daoMock = $this->createMock(PagamentoDAO::class);

        $daoMock->method('buscarPorId')
            ->with(1)
            ->willReturn($pagamento);

        $controller = new PagamentoController($daoMock);

        $resultado = $controller->buscarPorId(1);

        $this->assertEquals($pagamento, $resultado);
    }
}
