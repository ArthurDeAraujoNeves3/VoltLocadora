# VoltLocadora

Sistema de locação de veículos desenvolvido em PHP puro para a disciplina de **Desenvolvimento Web Back-end** da **UNIFAP** (TD3 Backend).

---

## Equipe

| Nome | Matrícula |
| ---- | --------- |
| Arthur de Araujo Neves | 20251180324 |
| Natanael Marcelino | 20251130220 |
| Gabriel Rodrigues Silva | 20251180338 |
| David Watylla Tavares de Oliveira | 20251180322 |
| Pedro Emanuel | — |

---

## Pré-requisitos

- [Docker](https://www.docker.com) + Docker Compose

---

## Configuração e execução

```bash
# 1. Clone o repositório
git clone <url-do-repositorio>
cd VoltLocadora

# 2. Crie o arquivo de variáveis de ambiente
cp .env.example .env

# 3. Suba os contêineres (primeira vez: build + init do banco)
docker compose up -d --build
```

Acesse em: [http://localhost:80](http://localhost:80)

> **Banco de dados:** disponível externamente em `localhost:3307` (use DBeaver, HeidiSQL ou similar)

### Reset completo (apaga todos os dados)

```bash
docker compose down -v && docker compose up -d --build
```

---

## Infraestrutura

| Serviço | Imagem | Porta |
|---------|--------|-------|
| **www** (Apache + PHP) | `php:apache` + extensão `pdo_mysql` | `80` |
| **mysql** | `mysql:8.0` | `3307` → `3306` |

O banco `topcar` é criado e populado automaticamente na primeira inicialização via `locadora_veiculos.sql`.

---

## Arquitetura do sistema

O projeto segue o padrão **MVC com camada DAO** em PHP puro, sem frameworks.

```
/
├── config/         → Conexão PDO (Singleton) e helpers globais
├── model/          → Entidades (atributos + getters/setters)
├── dao/            → Acesso ao banco (SQL via PDO prepared statements)
├── controller/     → Lógica de negócio (orquestra model + dao)
├── view/           → Templates HTML com PHP mínimo
├── index.php       → Único entry point — roteamento via ?page=
├── locadora_veiculos.sql  → DDL + dados de exemplo
├── Dockerfile      → Imagem PHP/Apache com pdo_mysql
├── docker-compose.yml
└── mysql.cnf       → Configuração de charset utf8mb4
```

### Fluxo de uma requisição

```
Browser → index.php
           ├── Lê ?page= e determina a entidade
           ├── require_once controller + model + dao da entidade
           ├── Processa POST (insert/update) ou GET (delete/edit)
           ├── Captura RuntimeException de regras de negócio
           ├── Carrega view/layout_header.php  (sidebar, CSS, flash message)
           ├── Carrega view/{entidade}_form.php (formulário + tabela)
           └── Carrega view/layout_footer.php
```

Não há redirecionamento após POST — o estado da página é re-renderizado com feedback inline.

### Decisões técnicas

- **PDO Singleton** em `config/Conexao.php` — uma única conexão por request
- **`h($val)`** em `config/helpers.php` — toda saída de variável nas views usa esta função (`htmlspecialchars` seguro para `null`)
- **SQL via `prepare()` com `?`** — sem concatenação de variáveis em queries
- **UTF-8 em três camadas**: header HTTP no PHP, `SET NAMES utf8mb4` na conexão, e `--character-set-server=utf8mb4` no MySQL

---

## Entidades e relacionamentos

```
marca          ←──────────────── veiculo ────────────────→ categoria
                                    ↑
                                    │
agencia ←── funcionario             │
   ↑              ↑                 │
   │              │                 │
   └──── locacao ─┘ ────────────────┘
              │
              ├── pagamento
              ├── seguro
              └── avaria
                                    │
manutencao ←────────────────── veiculo, funcionario
```

| Entidade | Depende de |
|----------|------------|
| `marca` | — |
| `categoria` | — |
| `agencia` | — |
| `cliente` | — |
| `funcionario` | agencia |
| `veiculo` | categoria, marca |
| `locacao` | cliente, veiculo, funcionario, agencia (retirada + devolução) |
| `pagamento` | locacao |
| `seguro` | locacao |
| `avaria` | locacao, funcionario |
| `manutencao` | veiculo, funcionario |

---

## Regras de negócio

### Veículos — Status possíveis

| Status | Significado |
|--------|-------------|
| `disponivel` | Pode ser locado |
| `locado` | Em uso por um cliente |
| `manutencao` | Em manutenção, indisponível |
| `inativo` | Fora de operação |

### Locação — Criação

1. **Disponibilidade de status:** o veículo deve estar com `status = 'disponivel'`. Veículos `locado`, `manutencao` ou `inativo` são recusados com mensagem de erro.
2. **Unicidade de locação ativa:** um veículo não pode ter duas locações com `status = 'aberta'` simultaneamente. A tentativa é bloqueada com erro.
3. **Atualização automática de status:** ao registrar uma locação com sucesso, o veículo é automaticamente alterado para `status = 'locado'`.

### Locação — Encerramento/Cancelamento

4. Ao alterar o status de uma locação de `aberta` para `encerrada` ou `cancelada`, o veículo vinculado volta automaticamente para `status = 'disponivel'`.
5. Ao **excluir** uma locação que estava `aberta`, o veículo também é liberado para `disponivel`.

### Locação — Status possíveis

| Status | Significado |
|--------|-------------|
| `aberta` | Em andamento |
| `encerrada` | Concluída normalmente |
| `cancelada` | Cancelada antes da retirada ou durante |

### Frontend — Validações

- O formulário de locação exibe apenas veículos disponíveis habilitados no `<select>`; veículos com outros status aparecem desabilitados com o status entre parênteses.
- Ao selecionar um veículo, o campo **Valor Diária** é preenchido automaticamente.
- Ao preencher as datas de retirada e devolução prevista, **Total de Dias** e **Valor Total** são calculados automaticamente via JavaScript.

### Pagamento — Status possíveis

| Status | Significado |
|--------|-------------|
| `pendente` | Aguardando confirmação |
| `aprovado` | Pagamento confirmado |
| `recusado` | Pagamento recusado |
| `estornado` | Pagamento estornado |

### Avaria — Status possíveis

| Status | Significado |
|--------|-------------|
| `pendente` | Registrada, aguardando reparo |
| `em_reparo` | Em processo de reparo |
| `concluida` | Reparo finalizado |

### Manutenção — Status possíveis

| Status | Significado |
|--------|-------------|
| `aberta` | Entrada registrada |
| `em_andamento` | Manutenção em execução |
| `concluida` | Veículo liberado |

---

## Módulos do sistema

### Cadastros base
- **Marcas** — marcas dos veículos (Volkswagen, Toyota etc.)
- **Categorias** — classificação da frota (Econômico, SUV, Premium etc.) com código único
- **Agências** — filiais da locadora com CNPJ, endereço e flag de matriz
- **Funcionários** — vinculados a uma agência, com cargo e status ativo/inativo
- **Clientes** — CPF, CNH e vencimento da CNH obrigatórios

### Operações
- **Veículos** — frota completa com modelo, placa, chassi, quilometragem, combustível e valor de diária
- **Locações** — contrato entre cliente, veículo, funcionário e agências de retirada/devolução
- **Pagamentos** — formas: crédito, débito, PIX, dinheiro, boleto; suporte a parcelamento
- **Seguros** — apólice vinculada à locação com valor de franquia e diária do seguro
- **Avarias** — danos ocorridos durante a locação com controle de cobrança ao cliente
- **Manutenções** — preventiva, corretiva ou revisão com controle de entrada/saída e custo

---

## Banco de dados

Banco: `topcar` · Charset: `utf8mb4` · Collation: `utf8mb4_unicode_ci`

O arquivo `locadora_veiculos.sql` contém:
- DDL completo com `CREATE TABLE IF NOT EXISTS` (idempotente)
- Dados de exemplo: 5 marcas, 5 categorias, 3 agências, 4 funcionários, 4 clientes, 5 veículos, 4 locações, 3 pagamentos, 3 seguros, 1 avaria, 2 manutenções

Todos os INSERTs usam `INSERT IGNORE INTO` para evitar duplicatas em re-execuções.

---

## Variáveis de ambiente

| Variável | Descrição | Padrão |
|----------|-----------|--------|
| `MYSQL_USERNAME` | Usuário do banco | `mysql` |
| `MYSQL_PASSWORD` | Senha do usuário | `pass3word` |
| `MYSQL_ROOT_PASSWORD` | Senha do root | `pass3word` |
| `MYSQL_ALLOW_EMPTY_PASSWORD` | Permitir senha vazia | `false` |
| `MYSQL_RANDOM_ROOT_PASSWORD` | Senha root aleatória | `false` |
