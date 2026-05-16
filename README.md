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
| Pedro Emanuel Ribeiro dos Santos | 20251180362 |

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
| ------- | ------ | ----- |
| **www** (Apache + PHP) | `php:apache` + `pdo_mysql` | `80` |
| **mysql** | `mysql:8.0` | `3307 → 3306` |

O banco `topcar` é criado e populado automaticamente na primeira inicialização via `locadora_veiculos.sql`.

---

## Arquitetura do sistema

O projeto segue o padrão **MVC com camada DAO** em PHP puro, sem frameworks.

```text
/
├── config/                → Conexão PDO (Singleton) e helpers globais
├── model/                 → Entidades (atributos + getters/setters)
├── dao/                   → Acesso ao banco (SQL via PDO prepared statements)
├── controller/            → Lógica de negócio (orquestra model + dao)
├── view/                  → Templates HTML com PHP mínimo
├── index.php              → Único entry point — roteamento via ?page=
├── locadora_veiculos.sql  → DDL + dados de exemplo
├── Dockerfile             → Imagem PHP/Apache com pdo_mysql
├── docker-compose.yml
└── mysql.cnf              → Configuração de charset utf8mb4
```

### Fluxo de uma requisição

```text
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
- **UTF-8 em três camadas**: header HTTP no PHP, `SET NAMES utf8mb4` na conexão e `--character-set-server=utf8mb4` no MySQL

---

## Entidades e relacionamentos

```text
marca        ←──── veiculo ────→ categoria
                      ↑
agencia ←── funcionario
   ↑              ↑
   └──── locacao ─┘
              │
              ├── pagamento
              ├── seguro (1:1)
              └── avaria

manutencao ←── veiculo, funcionario
```

| Entidade | Depende de |
| -------- | ---------- |
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

As regras estão implementadas na camada de controller e dao, e disparadas como `RuntimeException` com mensagem para o usuário quando violadas.

---

### Locação

#### Criar nova locação

1. O veículo precisa ter `status = 'disponivel'`. Se estiver `locado`, `manutencao` ou `inativo`, a locação é bloqueada com mensagem de erro.
2. O veículo não pode ter outra locação com `status = 'aberta'`. Cada veículo só pode estar em uma locação ativa por vez.
3. A data de devolução prevista não pode ser anterior à data de retirada.
4. O `total_dias` e o `valor_total` são sempre calculados no servidor (`total_dias = diferença em dias entre retirada e devolução; mínimo 1 dia`). Valores enviados via formulário são ignorados.
5. Toda nova locação é criada obrigatoriamente com `status = 'aberta'`, independentemente do que for enviado no formulário.
6. Ao salvar, o veículo é automaticamente alterado para `status = 'locado'`.

#### Editar locação existente

7. Se a locação estava `aberta` e o usuário troca o veículo por outro, o veículo anterior é liberado para `'disponivel'` e o novo veículo é validado e bloqueado como `'locado'`.
8. O novo veículo deve estar `disponivel` e sem outra locação aberta para poder ser selecionado.
9. Se a locação for alterada de `aberta` para `encerrada` ou `cancelada`, o veículo vinculado volta automaticamente para `'disponivel'`.

#### Excluir locação

10. Ao excluir uma locação com `status = 'aberta'`, o veículo vinculado é liberado automaticamente para `'disponivel'`.
11. Uma locação que possui `pagamento` ou `avaria` vinculados não pode ser excluída (FK `RESTRICT`). Remova os vínculos antes.

---

### Veículo

12. Veículos com `status = 'disponivel'` são exibidos habilitados no formulário de locação. Veículos com outros status aparecem desabilitados com o status entre parênteses.
13. Não é possível alterar o status de um veículo que possui locação em aberto para nenhum valor diferente de `'locado'`. A edição é bloqueada com mensagem de erro.
14. Não é possível excluir um veículo que possui locação em aberto.
15. Veículos vinculados a locações, manutenções ou outras entidades também não podem ser excluídos (FK `RESTRICT`).
16. Placa e chassi são únicos no banco — duplicata gera erro com mensagem amigável.

---

### Seguro

17. Cada locação admite **no máximo um seguro**. Tentar cadastrar um segundo seguro para a mesma locação é bloqueado com mensagem de erro.
18. Ao excluir uma locação, o seguro vinculado é excluído automaticamente (FK `CASCADE`).

---

### Integridade referencial (todas as entidades)

19. Nenhum registro-pai pode ser excluído enquanto houver registros filhos vinculados. Exemplos:
    - Marca com veículos → bloqueada
    - Agência com funcionários ou locações → bloqueada
    - Cliente com locações → bloqueado
    - Funcionário com locações, avarias ou manutenções → bloqueado
    - Locação com pagamentos ou avarias → bloqueada
20. Todos os erros de FK (`MySQL 1451`) e de duplicata (`MySQL 1062`) são capturados e exibidos como mensagem amigável — nenhum Fatal Error é exposto ao usuário.

---

### Dados únicos no banco

| Campo | Entidade | Regra |
| ----- | -------- | ----- |
| `cpf` | funcionario, cliente | Único por tabela |
| `cnh` | cliente | Único |
| `cnpj` | agencia | Único |
| `codigo` | categoria | Único |
| `placa` | veiculo | Único |
| `chassi` | veiculo | Único |
| `id_locacao` | seguro | Único (1 seguro por locação) |

---

### Status possíveis por entidade

**Veículo**

| Status | Significado |
| ------ | ----------- |
| `disponivel` | Pode ser locado |
| `locado` | Em uso — gerenciado automaticamente pela locação |
| `manutencao` | Em manutenção, indisponível para locação |
| `inativo` | Fora de operação |

**Locação**

| Status | Significado |
| ------ | ----------- |
| `aberta` | Em andamento — veículo está locado |
| `encerrada` | Devolvido normalmente — veículo liberado |
| `cancelada` | Cancelada — veículo liberado |

**Pagamento**

| Status | Significado |
| ------ | ----------- |
| `pendente` | Aguardando confirmação |
| `aprovado` | Confirmado |
| `recusado` | Recusado pela operadora |
| `estornado` | Estornado após aprovação |

**Avaria**

| Status | Significado |
| ------ | ----------- |
| `pendente` | Registrada, aguardando reparo |
| `em_reparo` | Em processo de reparo |
| `concluida` | Reparo finalizado |

**Manutenção**

| Status | Significado |
| ------ | ----------- |
| `aberta` | Entrada registrada |
| `em_andamento` | Manutenção em execução |
| `concluida` | Concluída, veículo liberado |

---

## Módulos do sistema

### Cadastros base

- **Marcas** — marcas dos veículos (ex.: Volkswagen, Toyota)
- **Categorias** — classificação da frota (ex.: Econômico, SUV, Premium) com código único
- **Agências** — filiais da locadora com CNPJ, endereço e flag de matriz/filial
- **Funcionários** — vinculados a uma agência, com cargo e status ativo/inativo
- **Clientes** — CPF, CNH e vencimento da CNH obrigatórios

### Operações

- **Veículos** — frota com modelo, placa, chassi, quilometragem, combustível e valor de diária
- **Locações** — contrato entre cliente, veículo, funcionário e agências de retirada/devolução; calcula dias e valor total automaticamente
- **Pagamentos** — formas: crédito, débito, PIX, dinheiro, boleto; suporte a parcelamento
- **Seguros** — apólice 1:1 com locação; franquia e valor de diária do seguro
- **Avarias** — danos durante a locação com controle de cobrança ao cliente
- **Manutenções** — preventiva, corretiva ou revisão com entrada/saída e custo

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
| -------- | --------- | ------ |
| `MYSQL_USERNAME` | Usuário do banco | `mysql` |
| `MYSQL_PASSWORD` | Senha do usuário | `pass3word` |
| `MYSQL_ROOT_PASSWORD` | Senha do root | `pass3word` |
| `MYSQL_ALLOW_EMPTY_PASSWORD` | Permitir senha vazia | `false` |
| `MYSQL_RANDOM_ROOT_PASSWORD` | Senha root aleatória | `false` |
