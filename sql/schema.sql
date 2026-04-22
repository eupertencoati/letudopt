-- letudo.pt - Base de Dados MySQL
CREATE DATABASE IF NOT EXISTS letudo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE letudo;

CREATE TABLE Utilizadores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_utilizador VARCHAR(100) UNIQUE NOT NULL,
    palavra_passe VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'comprador') DEFAULT 'comprador',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Produtos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    autor VARCHAR(255),
    categoria VARCHAR(100),
    preco DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL DEFAULT 0,
    imagem VARCHAR(255) DEFAULT 'img/placeholder.jpg',
    descricao TEXT,
    data_adicao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Encomendas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilizador_id INT NULL,
    nome_cliente VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    morada TEXT NOT NULL,
    produtos_json JSON NOT NULL,
    quantidade_total INT NOT NULL,
    preco_total DECIMAL(10,2) NOT NULL,
    data_encomenda DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilizador_id) REFERENCES Utilizadores(id) ON DELETE SET NULL
);

INSERT INTO Utilizadores (nome_utilizador, palavra_passe, tipo) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
