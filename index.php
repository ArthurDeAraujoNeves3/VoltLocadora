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

// Captura PDOException e RuntimeException em qualquer operação de escrita
function tentarAcao(callable $fn, string $msgSucesso, &$msg, &$msgType): void {
    try {
        $fn();
        $msg     = $msgSucesso;
        $msgType = 'success';
    } catch (RuntimeException $e) {
        $msg     = $e->getMessage();
        $msgType = 'danger';
    } catch (\PDOException $e) {
        $msgType = 'danger';
        // FK RESTRICT / violação de integridade
        if (strpos($e->getMessage(), '1451') !== false || strpos($e->getMessage(), 'foreign key') !== false) {
            $msg = 'Não é possível excluir: existem registros vinculados a este item. Remova os vínculos antes.';
        } elseif (strpos($e->getMessage(), '1062') !== false || strpos($e->getMessage(), 'Duplicate') !== false) {
            $msg = 'Operação inválida: já existe um registro com estes dados (CPF, CNH, placa ou código duplicado).';
        } else {
            $msg = 'Erro no banco de dados. Tente novamente.';
        }
    }
}

switch ($page) {

    // ── MARCA ─────────────────────────────────────────────────
    case 'marca':
        require_once 'controller/MarcaController.php';
        $ctrl = new MarcaController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            tentarAcao(function() use ($ctrl, $id) {
                if ($id) $ctrl->atualizar($id, $_POST['nome'], $_POST['pais_origem'] ?? '');
                else     $ctrl->salvar($_POST['nome'], $_POST['pais_origem'] ?? '');
            }, $id ? 'Marca atualizada!' : 'Marca salva!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Marca excluída!', $msg, $msgType);
        }
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
            tentarAcao(function() use ($ctrl, $id) {
                if ($id) $ctrl->atualizar($id, $_POST['nome'], $_POST['descricao'] ?? '', $_POST['codigo']);
                else     $ctrl->salvar($_POST['nome'], $_POST['descricao'] ?? '', $_POST['codigo']);
            }, $id ? 'Categoria atualizada!' : 'Categoria salva!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Categoria excluída!', $msg, $msgType);
        }
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
            $id    = $_POST['id'] ?? '';
            $matriz = isset($_POST['matriz']) ? 1 : 0;
            $args  = [$_POST['nome'], $_POST['cnpj'], $_POST['endereco'] ?? '', $_POST['cidade'] ?? '', $_POST['estado'] ?? '', $_POST['cep'] ?? '', $_POST['telefone'] ?? '', $_POST['email'] ?? '', $matriz];
            tentarAcao(function() use ($ctrl, $id, $args) {
                if ($id) $ctrl->atualizar($id, ...$args);
                else     $ctrl->salvar(...$args);
            }, $id ? 'Agência atualizada!' : 'Agência salva!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Agência excluída!', $msg, $msgType);
        }
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
            $id   = $_POST['id'] ?? '';
            $ativo = isset($_POST['ativo']) ? 1 : 0;
            $args = [$_POST['id_agencia'], $_POST['nome'], $_POST['cpf'], $_POST['cargo'] ?? '', $_POST['email'] ?? '', $_POST['telefone'] ?? '', $_POST['data_admissao'] ?? '', $ativo];
            tentarAcao(function() use ($ctrl, $id, $args) {
                if ($id) $ctrl->atualizar($id, ...$args);
                else     $ctrl->salvar(...$args);
            }, $id ? 'Funcionário atualizado!' : 'Funcionário salvo!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Funcionário excluído!', $msg, $msgType);
        }
        $objEdit      = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista        = $ctrl->listar();
        $agencias     = (new AgenciaController())->listar();
        $agenciasMap  = buildMap($agencias, 'getId', 'getNome');
        require_once 'view/layout_header.php';
        require_once 'view/funcionario_form.php';
        break;

    // ── CLIENTE ────────────────────────────────────────────────
    case 'cliente':
        require_once 'controller/ClienteController.php';
        $ctrl = new ClienteController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id   = $_POST['id'] ?? '';
            $ativo = isset($_POST['ativo']) ? 1 : 0;
            $args = [$_POST['nome'], $_POST['cpf'], $_POST['email'] ?? '', $_POST['telefone'] ?? '', $_POST['cnh'], $_POST['vencimento_cnh'] ?? '', $_POST['endereco'] ?? '', $_POST['cidade'] ?? '', $_POST['estado'] ?? '', $_POST['cep'] ?? '', $ativo];
            tentarAcao(function() use ($ctrl, $id, $args) {
                if ($id) $ctrl->atualizar($id, ...$args);
                else     $ctrl->salvar(...$args);
            }, $id ? 'Cliente atualizado!' : 'Cliente salvo!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Cliente excluído!', $msg, $msgType);
        }
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
            $id   = $_POST['id'] ?? '';
            $args = [$_POST['id_categoria'], $_POST['id_marca'], $_POST['modelo'], $_POST['ano_fabricacao'], $_POST['ano_modelo'], $_POST['placa'], $_POST['chassi'], $_POST['cor'] ?? '', $_POST['valor_diaria'], $_POST['status'], $_POST['quilometragem'] ?? 0, $_POST['combustivel'], $_POST['num_portas'] ?? 4, $_POST['capacidade_passageiros'] ?? 5];
            tentarAcao(function() use ($ctrl, $id, $args) {
                if ($id) $ctrl->atualizar($id, ...$args);
                else     $ctrl->salvar(...$args);
            }, $id ? 'Veículo atualizado!' : 'Veículo salvo!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Veículo excluído!', $msg, $msgType);
        }
        $objEdit       = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista         = $ctrl->listar();
        $marcas        = (new MarcaController())->listar();
        $categorias    = (new CategoriaController())->listar();
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
            $id   = $_POST['id'] ?? '';
            $args = [$_POST['id_cliente'], $_POST['id_veiculo'], $_POST['id_funcionario'], $_POST['id_agencia_retirada'], $_POST['id_agencia_devolucao'], $_POST['data_retirada'], $_POST['data_devolucao_prevista'], $_POST['data_devolucao_real'] ?? '', $_POST['valor_diaria'], $_POST['total_dias'] ?? 1, $_POST['valor_total'] ?? 0, $_POST['status'], $_POST['observacoes'] ?? ''];
            tentarAcao(function() use ($ctrl, $id, $args) {
                if ($id) $ctrl->atualizar($id, ...$args);
                else     $ctrl->salvar(...$args);
            }, $id ? 'Locação atualizada com sucesso!' : 'Locação registrada com sucesso!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Locação excluída e veículo liberado!', $msg, $msgType);
        }
        $objEdit       = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista         = $ctrl->listar();
        $clientes      = (new ClienteController())->listar();
        $veiculos      = (new VeiculoController())->listar();
        $funcionarios  = (new FuncionarioController())->listar();
        $agencias      = (new AgenciaController())->listar();
        $clientesMap   = buildMap($clientes, 'getId', 'getNome');
        $veiculosMap   = buildMap($veiculos, 'getId', 'getModelo');
        require_once 'view/layout_header.php';
        require_once 'view/locacao_form.php';
        break;

    // ── PAGAMENTO ──────────────────────────────────────────────
    case 'pagamento':
        require_once 'controller/PagamentoController.php';
        require_once 'controller/LocacaoController.php';
        $ctrl = new PagamentoController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id     = $_POST['id'] ?? '';
            $dataPag = !empty($_POST['data_pagamento']) ? str_replace('T', ' ', $_POST['data_pagamento']) : date('Y-m-d H:i:s');
            $args   = [$_POST['id_locacao'], $_POST['valor'], $_POST['forma_pagamento'], $_POST['status'], $_POST['codigo_transacao'] ?? '', $dataPag, $_POST['parcelas'] ?? 1];
            tentarAcao(function() use ($ctrl, $id, $args) {
                if ($id) $ctrl->atualizar($id, ...$args);
                else     $ctrl->salvar(...$args);
            }, $id ? 'Pagamento atualizado!' : 'Pagamento registrado!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Pagamento excluído!', $msg, $msgType);
        }
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
            $id   = $_POST['id'] ?? '';
            $args = [$_POST['id_locacao'], $_POST['tipo'], $_POST['cobertura'] ?? '', $_POST['valor_franquia'] ?? 0, $_POST['valor_diaria'] ?? 0, $_POST['apolice'] ?? ''];
            tentarAcao(function() use ($ctrl, $id, $args) {
                if ($id) $ctrl->atualizar($id, ...$args);
                else     $ctrl->salvar(...$args);
            }, $id ? 'Seguro atualizado!' : 'Seguro salvo!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Seguro excluído!', $msg, $msgType);
        }
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
            $id      = $_POST['id'] ?? '';
            $cobrado = isset($_POST['cobrado_cliente']) ? 1 : 0;
            $args    = [$_POST['id_locacao'], $_POST['id_funcionario'], $_POST['descricao'], $_POST['valor_reparo'] ?? 0, $_POST['status'], $cobrado];
            tentarAcao(function() use ($ctrl, $id, $args) {
                if ($id) $ctrl->atualizar($id, ...$args);
                else     $ctrl->salvar(...$args);
            }, $id ? 'Avaria atualizada!' : 'Avaria registrada!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Avaria excluída!', $msg, $msgType);
        }
        $objEdit         = ($acao === 'editar' && isset($_GET['id'])) ? $ctrl->buscarPorId($_GET['id']) : null;
        $lista           = $ctrl->listar();
        $locacoes        = (new LocacaoController())->listar();
        $funcionarios    = (new FuncionarioController())->listar();
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
            $id   = $_POST['id'] ?? '';
            $args = [$_POST['id_veiculo'], $_POST['id_funcionario'], $_POST['tipo'], $_POST['descricao'] ?? '', $_POST['custo'] ?? 0, $_POST['data_entrada'], $_POST['data_saida'] ?? '', $_POST['status'], $_POST['quilometragem_entrada'] ?? 0];
            tentarAcao(function() use ($ctrl, $id, $args) {
                if ($id) $ctrl->atualizar($id, ...$args);
                else     $ctrl->salvar(...$args);
            }, $id ? 'Manutenção atualizada!' : 'Manutenção registrada!', $msg, $msgType);
        }
        if ($acao === 'deletar' && isset($_GET['id'])) {
            tentarAcao(fn() => $ctrl->deletar($_GET['id']), 'Manutenção excluída!', $msg, $msgType);
        }
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
        $veiculosList     = (new VeiculoController())->listar();
        $clientesList     = (new ClienteController())->listar();
        $locacoesList     = (new LocacaoController())->listar();
        $statsVeiculos    = count($veiculosList);
        $statsClientes    = count($clientesList);
        $statsLocacoes    = count($locacoesList);
        $statsDisponiveis = count(array_filter($veiculosList, fn($v) => $v->getStatus() === 'disponivel'));
        $statsAbertas     = count(array_filter($locacoesList, fn($l) => $l->getStatus() === 'aberta'));
        require_once 'view/layout_header.php';
        require_once 'view/home.php';
        break;
}

require_once 'view/layout_footer.php';
