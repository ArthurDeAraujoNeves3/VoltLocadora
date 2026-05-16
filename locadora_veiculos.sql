-- ============================================================
--  LOCADORA DE VEÍCULOS — MySQL / MariaDB
--  DDL + Dados de Exemplo
-- ============================================================

USE topcar;

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- MARCA
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS marca (
    id_marca       INT            NOT NULL AUTO_INCREMENT,
    nome           VARCHAR(100)   NOT NULL,
    pais_origem    VARCHAR(100),
    PRIMARY KEY (id_marca)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- CATEGORIA
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categoria (
    id_categoria   INT            NOT NULL AUTO_INCREMENT,
    nome           VARCHAR(100)   NOT NULL,
    descricao      TEXT,
    codigo         VARCHAR(20)    NOT NULL UNIQUE,
    PRIMARY KEY (id_categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- AGENCIA
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS agencia (
    id_agencia     INT            NOT NULL AUTO_INCREMENT,
    nome           VARCHAR(150)   NOT NULL,
    cnpj           CHAR(18)       NOT NULL UNIQUE,
    endereco       VARCHAR(255),
    cidade         VARCHAR(100),
    estado         CHAR(2),
    cep            CHAR(9),
    telefone       VARCHAR(20),
    email          VARCHAR(150),
    matriz         TINYINT(1)     NOT NULL DEFAULT 0,
    PRIMARY KEY (id_agencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- FUNCIONARIO
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS funcionario (
    id_funcionario INT            NOT NULL AUTO_INCREMENT,
    id_agencia     INT            NOT NULL,
    nome           VARCHAR(150)   NOT NULL,
    cpf            CHAR(14)       NOT NULL UNIQUE,
    cargo          VARCHAR(80),
    email          VARCHAR(150),
    telefone       VARCHAR(20),
    data_admissao  DATE,
    ativo          TINYINT(1)     NOT NULL DEFAULT 1,
    PRIMARY KEY (id_funcionario),
    CONSTRAINT fk_func_agencia FOREIGN KEY (id_agencia)
        REFERENCES agencia (id_agencia) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- CLIENTE
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cliente (
    id_cliente      INT            NOT NULL AUTO_INCREMENT,
    nome            VARCHAR(150)   NOT NULL,
    cpf             CHAR(14)       NOT NULL UNIQUE,
    email           VARCHAR(150),
    telefone        VARCHAR(20),
    cnh             VARCHAR(20)    NOT NULL UNIQUE,
    vencimento_cnh  DATE,
    endereco        VARCHAR(255),
    cidade          VARCHAR(100),
    estado          CHAR(2),
    cep             CHAR(9),
    data_cadastro   DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ativo           TINYINT(1)     NOT NULL DEFAULT 1,
    PRIMARY KEY (id_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- VEICULO
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo (
    id_veiculo              INT              NOT NULL AUTO_INCREMENT,
    id_categoria            INT              NOT NULL,
    id_marca                INT              NOT NULL,
    modelo                  VARCHAR(100)     NOT NULL,
    ano_fabricacao          YEAR             NOT NULL,
    ano_modelo              YEAR             NOT NULL,
    placa                   VARCHAR(10)      NOT NULL UNIQUE,
    chassi                  VARCHAR(17)      NOT NULL UNIQUE,
    cor                     VARCHAR(50),
    valor_diaria            DECIMAL(10,2)    NOT NULL,
    status                  ENUM('disponivel','locado','manutencao','inativo')
                                             NOT NULL DEFAULT 'disponivel',
    quilometragem           INT              NOT NULL DEFAULT 0,
    combustivel             ENUM('flex','gasolina','etanol','diesel','eletrico','hibrido')
                                             NOT NULL DEFAULT 'flex',
    num_portas              TINYINT          NOT NULL DEFAULT 4,
    capacidade_passageiros  TINYINT          NOT NULL DEFAULT 5,
    PRIMARY KEY (id_veiculo),
    CONSTRAINT fk_veiculo_categoria FOREIGN KEY (id_categoria)
        REFERENCES categoria (id_categoria) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_veiculo_marca FOREIGN KEY (id_marca)
        REFERENCES marca (id_marca) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- LOCACAO
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS locacao (
    id_locacao              INT              NOT NULL AUTO_INCREMENT,
    id_cliente              INT              NOT NULL,
    id_veiculo              INT              NOT NULL,
    id_funcionario          INT              NOT NULL,
    id_agencia_retirada     INT              NOT NULL,
    id_agencia_devolucao    INT              NOT NULL,
    data_retirada           DATE             NOT NULL,
    data_devolucao_prevista DATE             NOT NULL,
    data_devolucao_real     DATE,
    valor_diaria            DECIMAL(10,2)    NOT NULL,
    total_dias              INT              NOT NULL,
    valor_total             DECIMAL(10,2)    NOT NULL,
    status                  ENUM('aberta','encerrada','cancelada')
                                             NOT NULL DEFAULT 'aberta',
    observacoes             TEXT,
    data_criacao            DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_locacao),
    CONSTRAINT fk_loc_cliente    FOREIGN KEY (id_cliente)           REFERENCES cliente    (id_cliente)    ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_loc_veiculo    FOREIGN KEY (id_veiculo)           REFERENCES veiculo    (id_veiculo)    ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_loc_funcionario FOREIGN KEY (id_funcionario)      REFERENCES funcionario(id_funcionario) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_loc_ag_ret     FOREIGN KEY (id_agencia_retirada)  REFERENCES agencia    (id_agencia)    ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_loc_ag_dev     FOREIGN KEY (id_agencia_devolucao) REFERENCES agencia    (id_agencia)    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- PAGAMENTO
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pagamento (
    id_pagamento       INT              NOT NULL AUTO_INCREMENT,
    id_locacao         INT              NOT NULL,
    valor              DECIMAL(10,2)    NOT NULL,
    forma_pagamento    ENUM('credito','debito','pix','dinheiro','boleto')
                                        NOT NULL,
    status             ENUM('pendente','aprovado','recusado','estornado')
                                        NOT NULL DEFAULT 'pendente',
    codigo_transacao   VARCHAR(100),
    data_pagamento     DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    parcelas           TINYINT          NOT NULL DEFAULT 1,
    PRIMARY KEY (id_pagamento),
    CONSTRAINT fk_pag_locacao FOREIGN KEY (id_locacao)
        REFERENCES locacao (id_locacao) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- SEGURO
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS seguro (
    id_seguro       INT              NOT NULL AUTO_INCREMENT,
    id_locacao      INT              NOT NULL UNIQUE,
    tipo            VARCHAR(80)      NOT NULL,
    cobertura       TEXT,
    valor_franquia  DECIMAL(10,2)    NOT NULL DEFAULT 0.00,
    valor_diaria    DECIMAL(10,2)    NOT NULL DEFAULT 0.00,
    apolice         VARCHAR(50),
    PRIMARY KEY (id_seguro),
    CONSTRAINT fk_seg_locacao FOREIGN KEY (id_locacao)
        REFERENCES locacao (id_locacao) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- AVARIA
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS avaria (
    id_avaria        INT              NOT NULL AUTO_INCREMENT,
    id_locacao       INT              NOT NULL,
    id_funcionario   INT              NOT NULL,
    descricao        TEXT             NOT NULL,
    valor_reparo     DECIMAL(10,2)    NOT NULL DEFAULT 0.00,
    status           ENUM('pendente','em_reparo','concluida')
                                      NOT NULL DEFAULT 'pendente',
    data_registro    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cobrado_cliente  TINYINT(1)       NOT NULL DEFAULT 0,
    PRIMARY KEY (id_avaria),
    CONSTRAINT fk_ava_locacao    FOREIGN KEY (id_locacao)     REFERENCES locacao    (id_locacao)     ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_ava_funcionario FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- MANUTENCAO
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS manutencao (
    id_manutencao         INT              NOT NULL AUTO_INCREMENT,
    id_veiculo            INT              NOT NULL,
    id_funcionario        INT              NOT NULL,
    tipo                  ENUM('preventiva','corretiva','revisao')
                                           NOT NULL DEFAULT 'preventiva',
    descricao             TEXT,
    custo                 DECIMAL(10,2)    NOT NULL DEFAULT 0.00,
    data_entrada          DATE             NOT NULL,
    data_saida            DATE,
    status                ENUM('aberta','em_andamento','concluida')
                                           NOT NULL DEFAULT 'aberta',
    quilometragem_entrada INT              NOT NULL,
    PRIMARY KEY (id_manutencao),
    CONSTRAINT fk_man_veiculo     FOREIGN KEY (id_veiculo)     REFERENCES veiculo    (id_veiculo)     ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_man_funcionario FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================
--  DADOS DE EXEMPLO
-- ============================================================

-- MARCAS
INSERT IGNORE INTO marca (nome, pais_origem) VALUES
  ('Volkswagen', 'Alemanha'),
  ('Chevrolet',  'Estados Unidos'),
  ('Toyota',     'Japão'),
  ('Hyundai',    'Coreia do Sul'),
  ('Fiat',       'Itália');

-- CATEGORIAS
INSERT IGNORE INTO categoria (nome, descricao, codigo) VALUES
  ('Econômico',   'Veículos compactos de baixo custo operacional', 'ECO'),
  ('Intermediário','Sedãs e hatches médios',                        'INT'),
  ('SUV',          'Utilitários esportivos',                        'SUV'),
  ('Premium',      'Veículos de luxo',                              'PRE'),
  ('Pickup',       'Caminhonetes',                                  'PIK');

-- AGENCIAS
INSERT IGNORE INTO agencia (nome, cnpj, endereco, cidade, estado, cep, telefone, email, matriz) VALUES
  ('Locadora Central',  '12.345.678/0001-90', 'Av. Brasil, 1000',    'São Paulo',     'SP', '01310-100', '(11) 3000-1000', 'central@locadora.com.br',   1),
  ('Filial Aeroporto',  '12.345.678/0002-71', 'Rod. dos Bandeirantes km 2', 'Guarulhos', 'SP', '07190-100', '(11) 3000-2000', 'aeroporto@locadora.com.br', 0),
  ('Filial Sul',        '12.345.678/0003-52', 'Rua XV de Novembro, 500', 'Curitiba',   'PR', '80020-310', '(41) 3000-3000', 'sul@locadora.com.br',       0);

-- FUNCIONARIOS
INSERT IGNORE INTO funcionario (id_agencia, nome, cpf, cargo, email, telefone, data_admissao, ativo) VALUES
  (1, 'Ana Paula Souza',    '111.222.333-44', 'Gerente',    'ana@locadora.com.br',    '(11) 91111-0001', '2019-03-15', 1),
  (1, 'Carlos Mendes',      '222.333.444-55', 'Atendente',  'carlos@locadora.com.br', '(11) 91111-0002', '2021-06-01', 1),
  (2, 'Fernanda Lima',      '333.444.555-66', 'Atendente',  'fernanda@locadora.com.br','(11)91111-0003', '2022-01-10', 1),
  (3, 'Ricardo Alves',      '444.555.666-77', 'Mecânico',   'ricardo@locadora.com.br','(41) 91111-0004', '2020-09-20', 1);

-- CLIENTES
INSERT IGNORE INTO cliente (nome, cpf, email, telefone, cnh, vencimento_cnh, endereco, cidade, estado, cep, data_cadastro, ativo) VALUES
  ('João da Silva',      '500.600.700-11', 'joao@email.com',    '(11) 98888-0001', 'CNH-00001111', '2027-05-10', 'Rua das Flores, 10',   'São Paulo', 'SP', '01001-000', '2023-01-15', 1),
  ('Maria Oliveira',     '501.601.701-22', 'maria@email.com',   '(11) 98888-0002', 'CNH-00002222', '2026-11-30', 'Rua do Comércio, 25',  'Campinas',  'SP', '13010-050', '2023-04-20', 1),
  ('Pedro Costa',        '502.602.702-33', 'pedro@email.com',   '(41) 98888-0003', 'CNH-00003333', '2028-03-01', 'Av. das Araucárias, 5','Curitiba',  'PR', '80040-000', '2024-02-08', 1),
  ('Lucia Ferreira',     '503.603.703-44', 'lucia@email.com',   '(85) 98888-0004', 'CNH-00004444', '2025-12-15', 'Rua Fortaleza, 99',    'Fortaleza', 'CE', '60110-000', '2024-07-11', 1);

-- VEICULOS
INSERT IGNORE INTO veiculo (id_categoria, id_marca, modelo, ano_fabricacao, ano_modelo, placa, chassi, cor, valor_diaria, status, quilometragem, combustivel, num_portas, capacidade_passageiros) VALUES
  (1, 5, 'Mobi',       2022, 2023, 'ABC-1234', 'CH000000000000001', 'Branco',   89.90,  'disponivel', 18000, 'flex',     4, 5),
  (1, 1, 'Polo',       2023, 2023, 'DEF-5678', 'CH000000000000002', 'Prata',   120.00,  'disponivel',  5000, 'flex',     4, 5),
  (2, 3, 'Corolla',    2022, 2022, 'GHI-9012', 'CH000000000000003', 'Preto',   180.00,  'locado',     32000, 'flex',     4, 5),
  (3, 4, 'Tucson',     2021, 2022, 'JKL-3456', 'CH000000000000004', 'Cinza',   250.00,  'disponivel', 41000, 'gasolina', 4, 5),
  (5, 2, 'S10',        2020, 2021, 'MNO-7890', 'CH000000000000005', 'Branco',  300.00,  'manutencao', 78000, 'diesel',   4, 5);

-- LOCACOES
INSERT IGNORE INTO locacao (id_cliente, id_veiculo, id_funcionario, id_agencia_retirada, id_agencia_devolucao, data_retirada, data_devolucao_prevista, data_devolucao_real, valor_diaria, total_dias, valor_total, status, observacoes) VALUES
  (1, 3, 2, 1, 1, '2025-04-01', '2025-04-07', '2025-04-07', 180.00, 6,  1080.00, 'encerrada', 'Entregue sem avarias'),
  (2, 1, 3, 2, 2, '2025-04-10', '2025-04-15', '2025-04-16',  89.90, 6,   539.40, 'encerrada', 'Devolvido com 1 dia de atraso'),
  (3, 2, 2, 1, 3, '2025-05-01', '2025-05-05', NULL,          120.00, 4,  480.00, 'aberta',    NULL),
  (4, 4, 3, 2, 2, '2025-05-03', '2025-05-10', NULL,          250.00, 7, 1750.00, 'aberta',    'Cliente solicitou cadeirinha infantil');

-- PAGAMENTOS
INSERT IGNORE INTO pagamento (id_locacao, valor, forma_pagamento, status, codigo_transacao, data_pagamento, parcelas) VALUES
  (1, 1080.00, 'credito', 'aprovado', 'TXN-AAA-001', '2025-04-07 10:30:00', 3),
  (2,  539.40, 'pix',     'aprovado', 'TXN-BBB-002', '2025-04-16 14:00:00', 1),
  (3,  480.00, 'debito',  'aprovado', 'TXN-CCC-003', '2025-05-01 09:15:00', 1);

-- SEGUROS
INSERT IGNORE INTO seguro (id_locacao, tipo, cobertura, valor_franquia, valor_diaria, apolice) VALUES
  (1, 'Básico',   'Cobertura contra terceiros',             1500.00, 20.00, 'APL-2025-001'),
  (2, 'Completo', 'Cobertura total, sem franquia',             0.00, 35.00, 'APL-2025-002'),
  (4, 'Básico',   'Cobertura contra terceiros',             2000.00, 25.00, 'APL-2025-003');

-- AVARIA
INSERT IGNORE INTO avaria (id_locacao, id_funcionario, descricao, valor_reparo, status, data_registro, cobrado_cliente) VALUES
  (2, 3, 'Arranhão no para-choque traseiro direito', 350.00, 'concluida', '2025-04-16 08:00:00', 1);

-- MANUTENCAO
INSERT IGNORE INTO manutencao (id_veiculo, id_funcionario, tipo, descricao, custo, data_entrada, data_saida, status, quilometragem_entrada) VALUES
  (5, 4, 'corretiva', 'Troca de pastilhas de freio e fluido',  650.00, '2025-04-20', '2025-04-22', 'concluida', 78000),
  (3, 4, 'preventiva','Revisão dos 30.000 km',                 320.00, '2025-05-05', NULL,          'aberta',    32000);
