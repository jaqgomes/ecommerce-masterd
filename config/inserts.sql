USE ecommerce;

-- Insert users
INSERT INTO utilizadores (nome, apelido, data_nascimento, morada, email, telefone, username, senha_hash, tipo)
VALUES
('Admin', 'System', '1990-01-15', 'Rua Admin 1', 'admin@ecommerce.com', '912345678', 'admin', '$2y$10$qPl63RAgv34.jnK0KDmuHuHd/UIy51BcVJQXv32v6mnEk2NtCrbE6', 'admin'),
('João', 'Silva', '1995-03-22', 'Rua Principal 10', 'joao.silva@email.com', '913456789', 'joaosilva', '$2y$10$qPl63RAgv34.jnK0KDmuHuHd/UIy51BcVJQXv32v6mnEk2NtCrbE6', 'cliente'),
('Maria', 'Costa', '1992-07-18', 'Avenida Central 5', 'maria.costa@email.com', '914567890', 'mariacosta', '$2y$10$qPl63RAgv34.jnK0KDmuHuHd/UIy51BcVJQXv32v6mnEk2NtCrbE6', 'cliente'),
('Pedro', 'Oliveira', '1998-11-30', 'Rua Lateral 20', 'pedro.oliveira@email.com', '915678901', 'pedrooliveira', '$2y$10$qPl63RAgv34.jnK0KDmuHuHd/UIy51BcVJQXv32v6mnEk2NtCrbE6', 'cliente');

-- Insert products
INSERT INTO produtos (nome, descricao, preco, stock, imagem)
VALUES
('Laptop Dell XPS', 'Laptop de alta performance com processador Intel i7', 1299.99, 15, 'laptop_dell_xps.jpg'),
('Mouse Logitech', 'Mouse sem fio com precisão óptica', 45.99, 50, 'mouse_logitech.jpg'),
('Teclado Mecânico RGB', 'Teclado mecânico com retroiluminação RGB', 129.99, 25, 'teclado_mecanico.jpg'),
('Monitor LG 27"', 'Monitor Full HD com painel IPS', 299.99, 10, 'monitor_lg_27.jpg'),
('Webcam HD', 'Webcam 1080p com microfone integrado', 79.99, 30, 'webcam_hd.jpg'),
('Headset Gamer', 'Headset com som surround 7.1', 149.99, 20, 'headset_gamer.jpg'),
('Mousepad Grande', 'Mousepad XL com base antiderrapante', 34.99, 40, 'mousepad_grande.jpg'),
('Hub USB 3.0', 'Hub com 4 portas USB 3.0 e alimentação', 59.99, 35, 'hub_usb.jpg');

-- Insert orders
INSERT INTO encomendas (id_utilizador, data_encomenda, total, estado)
VALUES
(2, '2026-05-15 10:30:00', 1449.98, 'entregue'),
(3, '2026-05-18 14:00:00', 299.99, 'enviado'),
(4, '2026-05-20 11:45:00', 679.96, 'pendente'),
(2, '2026-05-22 15:30:00', 179.98, 'entregue');

-- Insert order items
INSERT INTO itens_encomenda (id_encomenda, id_produto, quantidade, preco_unitario)
VALUES
(1, 1, 1, 1299.99),
(1, 2, 1, 45.99),
(1, 3, 1, 129.99),
(2, 4, 1, 299.99),
(3, 5, 1, 79.99),
(3, 6, 1, 149.99),
(3, 7, 2, 34.99),
(4, 2, 2, 45.99),
(4, 8, 1, 59.99);
