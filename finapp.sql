CREATE DATABASE IF NOT EXISTS finapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE finapp;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    perfil_investidor VARCHAR(30) DEFAULT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE movimentacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo ENUM('receita','despesa') NOT NULL,
    descricao VARCHAR(150) NOT NULL,
    categoria VARCHAR(80) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_movimentacao DATE NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE metas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(120) NOT NULL,
    valor_objetivo DECIMAL(10,2) NOT NULL,
    valor_atual DECIMAL(10,2) DEFAULT 0,
    prazo DATE NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE conteudos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    categoria VARCHAR(80) NOT NULL,
    resumo VARCHAR(300) NOT NULL,
    conteudo TEXT NOT NULL,
    icone VARCHAR(100) NOT NULL
);

INSERT INTO conteudos (titulo,categoria,resumo,conteudo,icone) VALUES
('Orçamento pessoal','Organização','Entenda por que registrar suas entradas e saídas é o primeiro passo.','Um orçamento pessoal ajuda a visualizar para onde o dinheiro está indo. Registre suas receitas, organize as despesas e compare os valores ao longo do mês. A ideia é criar clareza antes de tomar decisões.','fa-solid fa-wallet'),
('Receitas e despesas','Básico','Aprenda a diferenciar entradas, gastos fixos e gastos variáveis.','Receitas são os valores recebidos, como salário, bolsa ou trabalhos. Despesas são os gastos realizados. Custos fixos tendem a se manter estáveis, enquanto custos variáveis podem mudar conforme o consumo.','fa-solid fa-arrow-right-arrow-left'),
('Metas financeiras','Planejamento','Transforme um objetivo em um plano que possa ser acompanhado.','Uma boa meta possui um valor, um prazo e acompanhamento do progresso. No FINAPP, você pode registrar quanto deseja alcançar e visualizar o percentual já acumulado.','fa-solid fa-bullseye'),
('Reserva financeira','Planejamento','Por que ter uma reserva pode aumentar a segurança financeira.','Uma reserva financeira é um valor separado para situações inesperadas. Antes de pensar em investimentos mais complexos, é importante compreender suas receitas, despesas e capacidade de guardar dinheiro.','fa-solid fa-piggy-bank'),
('Renda fixa e variável','Investimentos','Conheça a diferença entre duas categorias de investimentos.','Renda fixa possui regras de rentabilidade definidas ou vinculadas a indicadores. Renda variável sofre oscilações de mercado e não possui rentabilidade previsível. O conhecimento sobre riscos é essencial.','fa-solid fa-chart-line'),
('Perfil do investidor','Investimentos','Conservador, moderado ou arrojado: entenda a ideia por trás da classificação.','O perfil do investidor busca representar a tolerância ao risco e os objetivos de cada pessoa. No FINAPP, o questionário possui finalidade educativa e não substitui uma avaliação profissional.','fa-solid fa-seedling');
