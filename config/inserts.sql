USE ecommerce;

-- Insert users
INSERT INTO utilizadores (nome, apelido, data_nascimento, morada, email, telefone, username, senha_hash, tipo)
VALUES
('Admin', 'System', '1990-01-15', 'Rua Admin 1', 'admin@ecommerce.com', '912345678', 'admin', '$2y$10$qPl63RAgv34.jnK0KDmuHuHd/UIy51BcVJQXv32v6mnEk2NtCrbE6', 'admin'),
('João', 'Silva', '1995-03-22', 'Rua Principal 10', 'joao.silva@email.com', '913456789', 'joaosilva', '$2y$10$qPl63RAgv34.jnK0KDmuHuHd/UIy51BcVJQXv32v6mnEk2NtCrbE6', 'cliente'),
('Maria', 'Costa', '1992-07-18', 'Avenida Central 5', 'maria.costa@email.com', '914567890', 'mariacosta', '$2y$10$qPl63RAgv34.jnK0KDmuHuHd/UIy51BcVJQXv32v6mnEk2NtCrbE6', 'cliente'),
('Pedro', 'Oliveira', '1998-11-30', 'Rua Lateral 20', 'pedro.oliveira@email.com', '915678901', 'pedrooliveira', '$2y$10$qPl63RAgv34.jnK0KDmuHuHd/UIy51BcVJQXv32v6mnEk2NtCrbE6', 'cliente');

-- Insert products
INSERT INTO produtos (nome, descricao, categoria, preco, stock, imagem)
VALUES
('Laptop Dell XPS', 'Laptop ultraportátil de ultima geração com processador Intel i7 de 12ª geração, 16GB RAM DDR5, SSD NVMe 512GB e écran FHD de 15.6 polegadas. Ideal para profissionais que necessitam máxima performance em design, programação e processamento de vídeo. Bateria de longa duração até 14h, peso apenas 1.8kg e esquema de arrefecimento avançado para operações contínuas.', 'Computadores', 1299.99, 15, 'laptop_dell_xps.png'),
('Mouse Logitech', 'Mouse sem fio de precisão óptica com sensor de 4000 DPI ajustável, proporcionando controlo máximo para jogadores e profissionais. Funciona até 24 meses com duas pilhas AA, conexão USB 2.4GHz estável e botões laterais programáveis. Design ergonómico confortável para longas sessões de trabalho ou gaming, compatível com Windows, Mac e Linux.', 'Periféricos', 45.99, 50, 'mouse_logitech.png'),
('Teclado Mecânico RGB', 'Teclado mecânico profissional com switches mecanizados de alta qualidade, retroiluminação RGB personalizável com 16 milhões de cores. Inclui repouso para pulsos em alumínio, 104 teclas anti-ghosting com resposta táctil imediata, switch time inferior a 1ms. Ideal para gamers, programadores e escritores que necessitam precisão e resistência. Compatível com Windows, Mac e Linux.', 'Periféricos', 129.99, 25, 'teclado_mecanico.png'),
('Monitor LG 27"', 'Monitor IPS Full HD de 27 polegadas com taxa de actualização 75Hz e tempo de resposta 5ms. Painel IPS garante ângulos de visão até 178° com cores precisas e vibrantes, perfeito para edição de imagem, design gráfico e trabalho de escritório. Inclui suporte ajustável em altura, conexões HDMI e DisplayPort, redução de luz azul para proteção ocular prolongada.', 'Monitores', 299.99, 10, 'monitor_lg_27.png'),
('Webcam HD', 'Webcam 1080p Full HD com sensor de vidro de alta qualidade, microfone integrado com redução de ruído ambiental e correção automática de iluminação. Campo de visão 78° ideal para videochamadas profissionais, aulas online e streaming. Suporte universal com clipe versátil, compatível com plataformas Windows, Mac e Linux, plug-and-play sem necessidade de drivers.', 'Periféricos', 79.99, 30, 'webcam_hd.png'),
('Headset Gamer', 'Headset profissional com som surround 7.1 virtual, driver de áudio 40mm de alta fidelidade e microfone com cancelamento de ruído activo. Padiolas de espuma com memória térmica para conforto em sessões prolongadas, peso apenas 250g. Compatível com PC, PlayStation 5, Xbox Series X/S e Switch. Cabo de nylon reforçado com comprimento de 2,5m e conector 3,5mm de 4 polos.', 'Áudio', 149.99, 20, 'headset_gamer.png'),
('Mousepad Grande', 'Mousepad XXL profissional com dimensões 900x400mm, proporcionando espaço generoso para rato e teclado. Superfície de borracha premium com base em silicone antiderrapante que garante estabilidade máxima em qualquer superfície. Bordas cosidas reforçadas com durabilidade prolongada, design minimalista cinzento que combina com qualquer setup. Lavável e resistente ao desgaste, recomendado para gamers e profissionais.', 'Acessórios', 34.99, 40, 'mousepad_grande.png'),
('Hub USB 3.0', 'Hub USB 3.0 compacto com 4 portas, transferência de dados até 5Gbps (10x mais rápido que USB 2.0). Suporta alimentação externa através de adaptador 2A incluído, garantindo energia suficiente para dispositivos de alto consumo. Construção em alumínio com dissipação térmica, compatível com Windows, Mac e Linux. Ideal para expandir conectividade de computadores ultraportáteis, televisões e consolas.', 'Acessórios', 59.99, 35, 'hub_usb.png');

-- Insert orders
INSERT INTO encomendas (id_utilizador, morada, data_encomenda, total, estado)
VALUES
(2, 'Rua Principal 10', '2026-05-15 10:30:00', 1449.98, 'entregue'),
(3, 'Avenida Central 5', '2026-05-18 14:00:00', 299.99, 'enviado'),
(4, 'Rua Lateral 20', '2026-05-20 11:45:00', 679.96, 'pendente'),
(2, 'Rua Principal 10', '2026-05-22 15:30:00', 179.98, 'entregue');

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
