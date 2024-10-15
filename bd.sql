CREATE DATABASE avaliafisio;

USE avaliafisio;

CREATE TABLE fisioterapeutas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);

CREATE TABLE pacientes (
    nome VARCHAR(100)  PRIMARY KEY,
    idade INT,
    patologia varchar(40),
    telefone varchar(11),
    fisioterapeuta_id INT,
    FOREIGN KEY (fisioterapeuta_id) REFERENCES fisioterapeutas(id)
);

CREATE TABLE evolucao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_nome varchar(100) NOT NULL,                -- Relaciona com a tabela de pacientes
    dia INT NOT NULL,                        -- Dia da evolução
    atividades TEXT NOT NULL,                -- Descrição dos exercícios
    feito ENUM('sim', 'nao') DEFAULT 'nao',  -- Indica se o exercício foi feito
    FOREIGN KEY (paciente_nome) REFERENCES pacientes(nome) ON DELETE CASCADE -- Relaciona com a tabela de pacientes
);