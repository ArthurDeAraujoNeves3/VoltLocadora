# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

VoltLocadora — PHP puro MVC + DAO, sistema de locação de veículos. Projeto universitário (UNIFAP · TD3 Backend). Sem frameworks.

## Docker — comandos essenciais

```bash
# Primeiro uso (ou após resetar dados)
cp .env.example .env
docker compose up -d --build

# Reset completo (apaga todos os dados e recria o banco)
docker compose down -v && docker compose up -d --build

# Logs do Apache/PHP
docker compose logs -f www

# Logs do MySQL
docker compose logs -f mysql

# Acessar shell do container PHP
docker compose exec www bash

# Acessar MySQL CLI
docker compose exec mysql mysql -u mysql -ppass3word topcar
```

App: `http://localhost:80` · MySQL externo: `localhost:3307`

O banco (`topcar`) é inicializado automaticamente via `locadora_veiculos.sql` montado em `docker-entrypoint-initdb.d/` — só roda quando o volume está vazio (primeiro `up`). Para forçar re-inicialização: `docker compose down -v`.

## Arquitetura

### Fluxo de request

```
Browser → index.php (único entry point)
            ├── switch($page) → require_once controller + model + dao
            ├── POST handling com try/catch (LocacaoController lança RuntimeException)
            ├── require_once view/layout_header.php  (sidebar + CSS)
            ├── require_once view/{entidade}_form.php
            └── require_once view/layout_footer.php
```

Roteamento: `?page=marca`, `?page=locacao&acao=editar&id=3`, etc. POST usa campo oculto `id` (vazio = insert, preenchido = update).

### Camadas

| Camada | Responsabilidade |
|--------|-----------------|
| `config/Conexao.php` | PDO Singleton. Lê env vars `MYSQL_HOST/DATABASE/USERNAME/PASSWORD`. Define `SET NAMES utf8mb4`. |
| `config/helpers.php` | Função `h($val)` — escape HTML seguro para null: `htmlspecialchars((string)($val ?? ''), ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8')`. Usar em **todos** os outputs de variáveis nas views. |
| `model/` | Entidades POPO: atributos privados, construtor com defaults, getters/setters. Sem SQL, sem lógica. |
| `dao/` | SQL via PDO `prepare()` + `?`. Retorna instâncias do Model (nunca arrays brutos). Construtor chama `Conexao::getConn()`. |
| `controller/` | Orquestra Model + DAO. Recebe escalares, monta objetos, delega ao DAO. Pode instanciar múltiplos DAOs quando a lógica de negócio exige. |
| `view/` | HTML + PHP mínimo. Variáveis injetadas pelo `index.php` via `require_once`. |

### Entidades e dependências

```
marca, categoria, agencia          ← sem FKs (independentes)
funcionario → agencia
veiculo     → categoria, marca
cliente                            ← sem FKs
locacao     → cliente, veiculo, funcionario, agencia (×2)
pagamento   → locacao
seguro      → locacao
avaria      → locacao, funcionario
manutencao  → veiculo, funcionario
```

### Lógica de negócio crítica (LocacaoController)

`LocacaoController` é o único controller com lógica além de CRUD simples:
- **salvar**: valida `veiculo.status === 'disponivel'` + sem locação aberta → lança `RuntimeException` → muda veículo para `'locado'`
- **atualizar**: ao encerrar/cancelar (`status → encerrada|cancelada`) → muda veículo para `'disponivel'`
- **deletar**: se locação estava `aberta` → libera veículo

`LocacaoDAO::veiculoPossuiLocacaoAberta($id)` verifica conflito antes de salvar.

Erros de negócio são capturados em `index.php` com `try/catch (RuntimeException $e)` e exibidos como `$msgType = 'danger'`.

## Convenções obrigatórias

- Todo output de variável na view: `<?= h($var) ?>` (nunca `echo $var` direto)
- SQL sempre via `prepare()` com `?` — zero concatenação de variável em query
- `require_once` para todos os includes (nunca `include`)
- Tabelas: `id` é sempre `id_{entidade}` (ex: `id_marca`, `id_cliente`)
- IDs do MySQL primário: `id_marca`, `id_categoria`, etc. — mapeados nos getters como `getId()`
- Nomes de arquivos de view: `{entidade}_form.php` (form + lista na mesma página)

## UTF-8

Três camadas garantem UTF-8 completo:
1. `index.php`: `header('Content-Type: text/html; charset=UTF-8')` + `mb_internal_encoding('UTF-8')`
2. `Conexao.php`: DSN com `charset=utf8mb4` + `SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci`
3. `mysql.cnf` + `docker-compose.yml` `command`: `--character-set-server=utf8mb4 --skip-character-set-client-handshake`

Se dados aparecerem com encoding errado: `docker compose down -v && docker compose up -d --build`.

## Adicionando nova entidade

1. `model/NovaEntidade.php` — atributos privados, construtor com defaults, getters/setters
2. `dao/NovaEntidadeDAO.php` — 5 métodos: `salvar`, `listar`, `atualizar`, `deletar`, `buscarPorId`
3. `controller/NovaEntidadeController.php` — delega ao DAO
4. `view/nova_entidade_form.php` — formulário + tabela, usa `h()` em todos os outputs
5. `index.php` — adicionar `case 'nova_entidade':` no switch
6. `view/layout_header.php` — adicionar link no sidebar
7. SQL: `CREATE TABLE IF NOT EXISTS nova_entidade (id_nova_entidade INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ...)`
