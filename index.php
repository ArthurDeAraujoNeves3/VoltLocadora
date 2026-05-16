<?php
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

require_once 'config/helpers.php';

$page    = $_GET['page']  ?? 'home';
$acao    = $_POST['acao'] ?? ($_GET['acao'] ?? '');
$msgType = 'success';

function buildMap(array $lista, string $getIdMethod, string $getLabelMethod): array {
    $map = [];
    foreach ($lista as $item) {
        $map[$item->$getIdMethod()] = $item->$getLabelMethod();
    }
    return $map;
}

switch ($page) {

    // ── MARCA ─────────────────────────────────────────────────
    case 'marca':
        require_once 'controller/MarcaController.php';
        $ctrl = new MarcaController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $nome = $_POST['nome'];
            $paisOrigem = $_POST['pais_origem'] ?? '';
            if ($id) { $ctrl->atualizar($id, $nome, $paisOrigem); $msg = 'Marca atualizada com sucesso!'; }
            else      { $ctrl->salvar($nome, $paisOrigem);          $msg = 'Marca salva com sucesso!'; }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) { $ctrl->deletar($_GET['id']); $msg = 'Marca excluída!'; }
        $objEdit = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista = $ctrl->listar();
        require_once 'view/layout_header.php';
        require_once 'view/marca_form.php';
        break;

    // ── CATEGORIA ──────────────────────────────────────────────
    case 'categoria':
        require_once 'controller/CategoriaController.php';
        $ctrl = new CategoriaController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $nome = $_POST['nome']; $descricao = $_POST['descricao'] ?? ''; $codigo = $_POST['codigo'];
            if ($id) { $ctrl->atualizar($id, $nome, $descricao, $codigo); $msg = 'Categoria atualizada!'; }
            else      { $ctrl->salvar($nome, $descricao, $codigo);          $msg = 'Categoria salva!'; }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) { $ctrl->deletar($_GET['id']); $msg = 'Categoria excluída!'; }
        $objEdit = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista = $ctrl->listar();
        require_once 'view/layout_header.php';
        require_once 'view/categoria_form.php';
        break;

    // ── AGÊNCIA ────────────────────────────────────────────────
    case 'agencia':
        require_once 'controller/AgenciaController.php';
        $ctrl = new AgenciaController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $matriz = isset($_POST['matriz']) ? 1 : 0;
            $args = [$_POST['nome'], $_POST['cnpj'], $_POST['endereco'] ?? '', $_POST['cidade'] ?? '', $_POST['estado'] ?? '', $_POST['cep'] ?? '', $_POST['telefone'] ?? '', $_POST['email'] ?? '', $matriz];
            if ($id) { $ctrl->atualizar($id, ...$args); $msg = 'Agência atualizada!'; }
            else      { $ctrl->salvar(...$args);          $msg = 'Agência salva!'; }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) { $ctrl->deletar($_GET['id']); $msg = 'Agência excluída!'; }
        $objEdit = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista = $ctrl->listar();
        require_once 'view/layout_header.php';
        require_once 'view/agencia_form.php';
        break;

    // ── FUNCIONÁRIO ────────────────────────────────────────────
    case 'funcionario':
        require_once 'controller/FuncionarioController.php';
        require_once 'controller/AgenciaController.php';
        $ctrl = new FuncionarioController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $ativo = isset($_POST['ativo']) ? 1 : 0;
            $args = [$_POST['id_agencia'], $_POST['nome'], $_POST['cpf'], $_POST['cargo'] ?? '', $_POST['email'] ?? '', $_POST['telefone'] ?? '', $_POST['data_admissao'] ?? '', $ativo];
            if ($id) { $ctrl->atualizar($id, ...$args); $msg = 'Funcionário atualizado!'; }
            else      { $ctrl->salvar(...$args);          $msg = 'Funcionário salvo!'; }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) { $ctrl->deletar($_GET['id']); $msg = 'Funcionário excluído!'; }
        $objEdit = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista = $ctrl->listar();
        $agencias = (new AgenciaController())->listar();
        $agenciasMap = buildMap($agencias, 'getId', 'getNome');
        require_once 'view/layout_header.php';
        require_once 'view/funcionario_form.php';
        break;

    // ── CLIENTE ────────────────────────────────────────────────
    case 'cliente':
        require_once 'controller/ClienteController.php';
        $ctrl = new ClienteController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $ativo = isset($_POST['ativo']) ? 1 : 0;
            $args = [$_POST['nome'], $_POST['cpf'], $_POST['email'] ?? '', $_POST['telefone'] ?? '', $_POST['cnh'], $_POST['vencimento_cnh'] ?? '', $_POST['endereco'] ?? '', $_POST['cidade'] ?? '', $_POST['estado'] ?? '', $_POST['cep'] ?? '', $ativo];
            if ($id) { $ctrl->atualizar($id, ...$args); $msg = 'Cliente atualizado!'; }
            else      { $ctrl->salvar(...$args);          $msg = 'Cliente salvo!'; }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) { $ctrl->deletar($_GET['id']); $msg = 'Cliente excluído!'; }
        $objEdit = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista = $ctrl->listar();
        require_once 'view/layout_header.php';
        require_once 'view/cliente_form.php';
        break;

    // ── VEÍCULO ────────────────────────────────────────────────
    case 'veiculo':
        require_once 'controller/VeiculoController.php';
        require_once 'controller/MarcaController.php';
        require_once 'controller/CategoriaController.php';
        $ctrl = new VeiculoController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $args = [$_POST['id_categoria'], $_POST['id_marca'], $_POST['modelo'], $_POST['ano_fabricacao'], $_POST['ano_modelo'], $_POST['placa'], $_POST['chassi'], $_POST['cor'] ?? '', $_POST['valor_diaria'], $_POST['status'], $_POST['quilometragem'] ?? 0, $_POST['combustivel'], $_POST['num_portas'] ?? 4, $_POST['capacidade_passageiros'] ?? 5];
            if ($id) { $ctrl->atualizar($id, ...$args); $msg = 'Veículo atualizado!'; }
            else      { $ctrl->salvar(...$args);          $msg = 'Veículo salvo!'; }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) { $ctrl->deletar($_GET['id']); $msg = 'Veículo excluído!'; }
        $objEdit = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista = $ctrl->listar();
        $marcas      = (new MarcaController())->listar();
        $categorias  = (new CategoriaController())->listar();
        $marcasMap     = buildMap($marcas, 'getId', 'getNome');
        $categoriasMap = buildMap($categorias, 'getId', 'getNome');
        require_once 'view/layout_header.php';
        require_once 'view/veiculo_form.php';
        break;

    // ── LOCAÇÃO ────────────────────────────────────────────────
    case 'locacao':
        require_once 'controller/LocacaoController.php';
        require_once 'controller/ClienteController.php';
        require_once 'controller/VeiculoController.php';
        require_once 'controller/FuncionarioController.php';
        require_once 'controller/AgenciaController.php';
        $ctrl = new LocacaoController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? '';
                $args = [$_POST['id_cliente'], $_POST['id_veiculo'], $_POST['id_funcionario'], $_POST['id_agencia_retirada'], $_POST['id_agencia_devolucao'], $_POST['data_retirada'], $_POST['data_devolucao_prevista'], $_POST['data_devolucao_real'] ?? '', $_POST['valor_diaria'], $_POST['total_dias'], $_POST['valor_total'], $_POST['status'], $_POST['observacoes'] ?? ''];
                if ($id) { $ctrl->atualizar($id, ...$args); $msg = 'Locação atualizada com sucesso!'; }
                else      { $ctrl->salvar(...$args);          $msg = 'Locação registrada com sucesso!'; }
            } catch (RuntimeException $e) {
                $msg = $e->getMessage();
                $msgType = 'danger';
            }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            $ctrl->deletar($_GET['id']);
            $msg = 'Locação excluída e veículo liberado!';
        }
        $objEdit = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista       = $ctrl->listar();
        $clientes    = (new ClienteController())->listar();
        $veiculos    = (new VeiculoController())->listar();
        $funcionarios = (new FuncionarioController())->listar();
        $agencias    = (new AgenciaController())->listar();
        $clientesMap  = buildMap($clientes, 'getId', 'getNome');
        $veiculosMap  = buildMap($veiculos, 'getId', 'getModelo');
        require_once 'view/layout_header.php';
        require_once 'view/locacao_form.php';
        break;

    // ── PAGAMENTO ──────────────────────────────────────────────
    case 'pagamento':
        require_once 'controller/PagamentoController.php';
        require_once 'controller/LocacaoController.php';
        $ctrl = new PagamentoController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $dataPag = !empty($_POST['data_pagamento']) ? str_replace('T', ' ', $_POST['data_pagamento']) : date('Y-m-d H:i:s');
            $args = [$_POST['id_locacao'], $_POST['valor'], $_POST['forma_pagamento'], $_POST['status'], $_POST['codigo_transacao'] ?? '', $dataPag, $_POST['parcelas'] ?? 1];
            if ($id) { $ctrl->atualizar($id, ...$args); $msg = 'Pagamento atualizado!'; }
            else      { $ctrl->salvar(...$args);          $msg = 'Pagamento registrado!'; }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) { $ctrl->deletar($_GET['id']); $msg = 'Pagamento excluído!'; }
        $objEdit  = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista    = $ctrl->listar();
        $locacoes = (new LocacaoController())->listar();
        require_once 'view/layout_header.php';
        require_once 'view/pagamento_form.php';
        break;

    // ── SEGURO ─────────────────────────────────────────────────
    case 'seguro':
        require_once 'controller/SeguroController.php';
        require_once 'controller/LocacaoController.php';
        $ctrl = new SeguroController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $args = [$_POST['id_locacao'], $_POST['tipo'], $_POST['cobertura'] ?? '', $_POST['valor_franquia'] ?? 0, $_POST['valor_diaria'] ?? 0, $_POST['apolice'] ?? ''];
            if ($id) { $ctrl->atualizar($id, ...$args); $msg = 'Seguro atualizado!'; }
            else      { $ctrl->salvar(...$args);          $msg = 'Seguro salvo!'; }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) { $ctrl->deletar($_GET['id']); $msg = 'Seguro excluído!'; }
        $objEdit  = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista    = $ctrl->listar();
        $locacoes = (new LocacaoController())->listar();
        require_once 'view/layout_header.php';
        require_once 'view/seguro_form.php';
        break;

    // ── AVARIA ─────────────────────────────────────────────────
    case 'avaria':
        require_once 'controller/AvariaController.php';
        require_once 'controller/LocacaoController.php';
        require_once 'controller/FuncionarioController.php';
        $ctrl = new AvariaController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $cobrado = isset($_POST['cobrado_cliente']) ? 1 : 0;
            $args = [$_POST['id_locacao'], $_POST['id_funcionario'], $_POST['descricao'], $_POST['valor_reparo'] ?? 0, $_POST['status'], $cobrado];
            if ($id) { $ctrl->atualizar($id, ...$args); $msg = 'Avaria atualizada!'; }
            else      { $ctrl->salvar(...$args);          $msg = 'Avaria registrada!'; }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) { $ctrl->deletar($_GET['id']); $msg = 'Avaria excluída!'; }
        $objEdit       = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista         = $ctrl->listar();
        $locacoes      = (new LocacaoController())->listar();
        $funcionarios  = (new FuncionarioController())->listar();
        $funcionariosMap = buildMap($funcionarios, 'getId', 'getNome');
        require_once 'view/layout_header.php';
        require_once 'view/avaria_form.php';
        break;

    // ── MANUTENÇÃO ─────────────────────────────────────────────
    case 'manutencao':
        require_once 'controller/ManutencaoController.php';
        require_once 'controller/VeiculoController.php';
        require_once 'controller/FuncionarioController.php';
        $ctrl = new ManutencaoController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $args = [$_POST['id_veiculo'], $_POST['id_funcionario'], $_POST['tipo'], $_POST['descricao'] ?? '', $_POST['custo'] ?? 0, $_POST['data_entrada'], $_POST['data_saida'] ?? '', $_POST['status'], $_POST['quilometragem_entrada'] ?? 0];
            if ($id) { $ctrl->atualizar($id, ...$args); $msg = 'Manutenção atualizada!'; }
            else      { $ctrl->salvar(...$args);          $msg = 'Manutenção registrada!'; }
        }
        if ($acao === 'deletar' && isset($_GET['id'])) { $ctrl->deletar($_GET['id']); $msg = 'Manutenção excluída!'; }
        $objEdit         = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista           = $ctrl->listar();
        $veiculos        = (new VeiculoController())->listar();
        $funcionarios    = (new FuncionarioController())->listar();
        $veiculosMap     = buildMap($veiculos, 'getId', 'getModelo');
        $funcionariosMap = buildMap($funcionarios, 'getId', 'getNome');
        require_once 'view/layout_header.php';
        require_once 'view/manutencao_form.php';
        break;

    // ── HOME ───────────────────────────────────────────────────
    default:
        require_once 'controller/VeiculoController.php';
        require_once 'controller/ClienteController.php';
        require_once 'controller/LocacaoController.php';
        $veiculosList  = (new VeiculoController())->listar();
        $clientesList  = (new ClienteController())->listar();
        $locacoesList  = (new LocacaoController())->listar();
        $statsVeiculos    = count($veiculosList);
        $statsClientes    = count($clientesList);
        $statsLocacoes    = count($locacoesList);
        $statsDisponiveis = count(array_filter($veiculosList, fn($v) => $v->getStatus() === 'disponivel'));
        $statsAbertas     = count(array_filter($locacoesList,  fn($l) => $l->getStatus() === 'aberta'));
        require_once 'view/layout_header.php';
        require_once 'view/home.php';
        break;
}

require_once 'view/layout_footer.php';
