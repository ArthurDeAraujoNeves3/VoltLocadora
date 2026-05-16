---
description: Regras para arquivos PHP neste projeto MVC + DAO
globs: ["**/*.php"]
---

# Regras do Projeto — PHP MVC + DAO

## Arquitetura Obrigatória

Este projeto segue **MVC com camada DAO**. Toda feature nova deve respeitar esta separação sem exceção.

```
/
├── config/        → Conexão PDO (Singleton)
├── model/         → Entidades (atributos + getters/setters)
├── dao/           → SQL e acesso ao banco
├── controller/    → Lógica de negócio (orquestra model + dao)
├── view/          → Templates HTML com PHP mínimo
└── index.php      → Roteamento via POST/GET
```

---

## Nomenclatura

| Camada     | Arquivo                                 | Classe                  |
|------------|-----------------------------------------|-------------------------|
| Config     | `config/Conexao.php`                    | `Conexao`               |
| Model      | `model/NomeEntidade.php`                | `NomeEntidade`          |
| DAO        | `dao/NomeEntidadeDAO.php`               | `NomeEntidadeDAO`       |
| Controller | `controller/NomeEntidadeController.php` | `NomeEntidadeController`|
| View       | `view/entidade_acao.php`                | —                       |

- Classes: **PascalCase**
- Métodos e variáveis: **camelCase**
- Tabelas no banco: **snake_case plural** (`filmes`, `usuarios`)
- Sem namespaces

---

## config/ — Conexão com o Banco

- Usar **PDO** com padrão **Singleton**
- Único ponto de abertura de conexão no projeto
- Nunca instanciar PDO fora de `Conexao.php`

```php
<?php
class Conexao {
    private static $instance;

    public static function getConn() {
        if (!isset(self::$instance)) {
            self::$instance = new \PDO(
                'mysql:host=localhost;dbname=NOME_BANCO',
                'USUARIO',
                'SENHA'
            );
        }
        return self::$instance;
    }
}
```

---

## model/ — Entidades

- Atributos todos **privados**
- Construtor recebe os campos principais com **valor padrão vazio**
- Um getter e um setter por atributo, em camelCase
- **Sem SQL, sem lógica de negócio, sem dependências externas**

```php
<?php
class NomeEntidade {
    private $id;
    private $campo;

    public function __construct($campo = '') {
        $this->campo = $campo;
    }

    public function getId()      { return $this->id; }
    public function getCampo()   { return $this->campo; }

    public function setId($id)   { $this->id = $id; }
    public function setCampo($v) { $this->campo = $v; }
}
```

---

## dao/ — Data Access Object

- Construtor **sempre** chama `Conexao::getConn()`
- Métodos obrigatórios: `salvar()`, `listar()`, `atualizar()`, `deletar($id)`, `buscarPorId($id)`
- **Todo SQL via `prepare()` com `?` como placeholder** — nunca concatenar variável em query
- `listar()` e `buscarPorId()` retornam **instâncias do Model**, nunca arrays brutos
- Hidratar objetos com construtor + setters

```php
<?php
require_once 'config/Conexao.php';
require_once 'model/NomeEntidade.php';

class NomeEntidadeDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function salvar(NomeEntidade $obj) {
        $stmt = $this->conn->prepare("INSERT INTO tabela (campo) VALUES (?)");
        $stmt->execute([$obj->getCampo()]);
    }

    public function listar() {
        $stmt = $this->conn->query("SELECT * FROM tabela");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new NomeEntidade($row['campo']);
            $obj->setId($row['id']);
            $lista[] = $obj;
        }
        return $lista;
    }

    public function atualizar(NomeEntidade $obj) {
        $stmt = $this->conn->prepare("UPDATE tabela SET campo = ? WHERE id = ?");
        $stmt->execute([$obj->getCampo(), $obj->getId()]);
    }

    public function deletar($id) {
        $stmt = $this->conn->prepare("DELETE FROM tabela WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tabela WHERE id = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new NomeEntidade($row['campo']);
            $obj->setId($row['id']);
            return $obj;
        }
        return null;
    }
}
```

---

## controller/ — Controlador

- Instancia DAO e Model **dentro de cada método** (sem injeção externa)
- Recebe parâmetros escalares (string, int) e monta o objeto Model internamente
- **Não executa SQL, não renderiza HTML**
- Métodos espelham os do DAO

```php
<?php
require_once 'model/NomeEntidade.php';
require_once 'dao/NomeEntidadeDAO.php';

class NomeEntidadeController {
    public function salvar($campo) {
        $obj = new NomeEntidade($campo);
        $dao = new NomeEntidadeDAO();
        $dao->salvar($obj);
    }

    public function listar() {
        $dao = new NomeEntidadeDAO();
        return $dao->listar();
    }

    public function atualizar($id, $campo) {
        $obj = new NomeEntidade($campo);
        $obj->setId($id);
        $dao = new NomeEntidadeDAO();
        $dao->atualizar($obj);
    }

    public function deletar($id) {
        $dao = new NomeEntidadeDAO();
        $dao->deletar($id);
    }

    public function buscarPorId($id) {
        $dao = new NomeEntidadeDAO();
        return $dao->buscarPorId($id);
    }
}
```

---

## view/ — Templates

- Apenas HTML + PHP mínimo para exibição de dados
- **Sem instâncias de DAO ou Controller**
- Variáveis injetadas pelo `index.php` via `require_once`
- Formulários: `method="post" action="index.php"`
- Campo oculto `id` para diferenciar insert de update
- Usar `isset()` para variáveis opcionais

```php
<form method="post" action="index.php">
    <input type="hidden" name="id"
           value="<?php echo isset($objEdit) ? $objEdit->getId() : ''; ?>">
    <label>Campo:
        <input type="text" name="campo" required
               value="<?php echo isset($objEdit) ? $objEdit->getCampo() : ''; ?>">
    </label>
    <button type="submit" name="acao" value="salvar">Salvar</button>
</form>
```

---

## index.php — Roteamento

- Único arquivo acessado pelo navegador
- Distingue ações por `$_SERVER['REQUEST_METHOD']` (POST) e `$_GET` (links)
- Ação identificada via `$_POST['acao']`
- Inclui arquivos na ordem: controller → model → view
- Feedback simples com `echo` antes da view

```php
<?php
require_once 'controller/NomeEntidadeController.php';
require_once 'model/NomeEntidade.php';

$controller = new NomeEntidadeController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campo = $_POST['campo'];
    $id    = $_POST['id'] ?? '';

    if ($_POST['acao'] === 'salvar') {
        if ($id) {
            $controller->atualizar($id, $campo);
            echo "<p>Atualizado com sucesso!</p>";
        } else {
            $controller->salvar($campo);
            echo "<p>Salvo com sucesso!</p>";
        }
    }
}

if (isset($_GET['editar'])) {
    $objEdit = $controller->buscarPorId($_GET['editar']);
}

if (isset($_GET['deletar'])) {
    $controller->deletar($_GET['deletar']);
    echo "<p>Deletado com sucesso!</p>";
}

require_once 'view/entidade_form.php';

$lista = $controller->listar();
echo "<h2>Lista</h2><ul>";
foreach ($lista as $item) {
    echo "<li>{$item->getCampo()} ";
    echo "<a href='?editar={$item->getId()}'>Editar</a> | ";
    echo "<a href='?deletar={$item->getId()}' onclick=\"return confirm('Excluir?');\">Excluir</a></li>";
}
echo "</ul>";
```

---

## Regras Gerais

- PHP puro — **sem frameworks**
- **`require_once`** para todos os includes, nunca `include` simples
- SQL **sempre** via `prepare()` com `?` — zero concatenação de variável em query
- Sem namespaces, sem abstração prematura (sem interfaces, traits ou classes base desnecessárias)
- Sem comentários óbvios — comentar somente o que não é evidente pelo código
- Toda tabela tem `id INT AUTO_INCREMENT PRIMARY KEY`

---

## Checklist para Nova Entidade

1. `model/NomeEntidade.php` — atributos, construtor, getters/setters
2. `dao/NomeEntidadeDAO.php` — 5 métodos padrão com PDO
3. `controller/NomeEntidadeController.php` — orquestra model + dao
4. `view/entidade_form.php` — formulário HTML
5. `index.php` — roteamento POST/GET
6. Tabela no MySQL com `id INT AUTO_INCREMENT PRIMARY KEY`

