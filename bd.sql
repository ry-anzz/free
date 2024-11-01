create database avaliafisio;
USE avaliafisio;


CREATE TABLE fisioterapeutas (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Adicionando um campo de ID para referenciar
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255),
    codigo_verificacao VARCHAR(6),
    data_expiracao_codigo DATETIME
);



CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    idade INT,
    patologia VARCHAR(40),
    conduta TEXT,                           -- Para comportar condutas mais longas
    telefone VARCHAR(11),
    fisioterapeuta_id INT,
    FOREIGN KEY (fisioterapeuta_id) REFERENCES fisioterapeutas(id) ON DELETE SET NULL
);


CREATE TABLE evolucao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,               -- Refere-se ao ID do paciente
    dia INT NOT NULL,                       
    atividades TEXT NOT NULL,               
    feito ENUM('sim', 'nao') DEFAULT 'nao', 
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE
);
