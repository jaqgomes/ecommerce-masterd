CREATE database ecommerce;
use ecommerce;
CREATE TABLE utilizadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    apelido VARCHAR(100) NOT NULL,
    data_nascimento VARCHAR(100) NOT NULL,
    morada VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20),
    username VARCHAR(50) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'cliente') DEFAULT 'cliente'
);
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    categoria VARCHAR(100),
    preco DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    imagem VARCHAR(255),
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE encomendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilizador INT NOT NULL,
    morada VARCHAR(100) NOT NULL,
    data_encomenda DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    estado ENUM('pendente', 'enviado', 'entregue', 'cancelado') DEFAULT 'pendente',
    FOREIGN KEY (id_utilizador) REFERENCES utilizadores(id)
);
CREATE TABLE itens_encomenda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_encomenda INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_encomenda) REFERENCES encomendas(id),
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
);