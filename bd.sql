CREATE DATABASE avaliafisio;

USE avaliafisio;

CREATE TABLE fisioterapeutas (
    nome VARCHAR(100) PRIMARY KEY,
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255),
    codigo_verificacao VARCHAR(6),    
    data_expiracao_codigo DATETIME
);

CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Adicionando uma coluna 'id' como chave primária
    nome VARCHAR(100),
    idade INT,
    patologia VARCHAR(40),
    conduta VARCHAR(2000),
    telefone VARCHAR(11),
    fisioterapeuta_id INT,
    FOREIGN KEY (fisioterapeuta_id) REFERENCES fisioterapeutas(id)
);
drop table pacientes;
CREATE TABLE evolucao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_nome varchar(100) NOT NULL,               
    dia INT NOT NULL,                        
    atividades TEXT NOT NULL,                
    feito ENUM('sim', 'nao') DEFAULT 'nao', 
    FOREIGN KEY (paciente_nome) REFERENCES pacientes(id) ON DELETE CASCADE 
);