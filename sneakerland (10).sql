-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21-Nov-2024 às 19:17
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sneakerland`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `enderecos_entrega`
--

CREATE TABLE `enderecos_entrega` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `endereco` varchar(255) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `cep` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `enderecos_entrega`
--

INSERT INTO `enderecos_entrega` (`id`, `usuario_id`, `endereco`, `cidade`, `estado`, `cep`) VALUES
(5, 20, 'Rua São Leopoldo, 463', 'Joinville', 'SC', '89206410');

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cargo` varchar(100) NOT NULL,
  `data_admissao` date NOT NULL,
  `salario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `usuario_id`, `cargo`, `data_admissao`, `salario`) VALUES
(3, 24, 'Funcionario', '2020-05-12', '123.00'),
(4, 27, 'Funcionario', '2025-12-30', '10.00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`id`, `pedido_id`, `produto_id`, `quantidade`, `preco`) VALUES
(1, 10, 3, 1, '120.00'),
(2, 10, 5, 1, '999.00'),
(3, 11, 5, 1, '999.00'),
(4, 12, 5, 1, '999.00'),
(5, 13, 3, 1, '120.00'),
(6, 14, 3, 1, '120.00'),
(7, 15, 3, 1, '120.00'),
(8, 16, 5, 1, '999.00'),
(9, 17, 5, 1, '999.00'),
(10, 18, 10, 1, '340.00'),
(11, 18, 5, 1, '999.00'),
(12, 18, 9, 1, '250.00'),
(13, 18, 7, 1, '550.00'),
(14, 18, 8, 1, '750.00'),
(15, 18, 3, 1, '120.00'),
(16, 19, 11, 1, '1200.00'),
(17, 20, 10, 1, '340.00'),
(18, 21, 7, 1, '550.00'),
(19, 22, 3, 1, '120.00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `status` enum('pendente','processando','concluido','cancelado') NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `endereco_entrega_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `data`, `status`, `total`, `endereco_entrega_id`) VALUES
(1, 20, '2024-10-29 18:27:56', 'pendente', '120.00', NULL),
(2, 20, '2024-10-29 18:39:59', 'pendente', '240.00', NULL),
(3, 20, '2024-10-29 18:42:57', 'pendente', '999.00', NULL),
(4, 20, '2024-10-29 18:43:32', 'pendente', '999.00', NULL),
(5, 20, '2024-10-29 18:46:14', 'pendente', '120.00', NULL),
(6, 20, '2024-10-29 14:48:16', 'pendente', '999.00', NULL),
(7, 20, '2024-10-29 14:51:26', 'pendente', '999.00', NULL),
(8, 20, '2024-10-29 14:53:46', 'pendente', '999.00', NULL),
(9, 20, '2024-10-29 14:54:07', 'pendente', '120.00', NULL),
(10, 20, '2024-10-29 15:03:31', 'concluido', '1119.00', NULL),
(11, 20, '2024-10-29 15:15:41', 'concluido', '999.00', NULL),
(12, 20, '2024-10-29 15:23:59', 'cancelado', '999.00', NULL),
(13, 20, '2024-10-29 15:24:58', 'cancelado', '120.00', NULL),
(14, 20, '2024-10-29 15:27:55', 'concluido', '120.00', NULL),
(15, 20, '2024-10-29 16:02:47', 'concluido', '120.00', 5),
(16, 20, '2024-10-29 16:04:43', 'cancelado', '999.00', 5),
(17, 20, '2024-10-29 16:48:32', 'cancelado', '999.00', 5),
(18, 20, '2024-10-31 16:13:50', 'pendente', '3009.00', 5),
(19, 20, '2024-11-21 14:43:32', 'pendente', '1200.00', 5),
(20, 20, '2024-11-21 14:44:13', 'pendente', '340.00', 5),
(21, 20, '2024-11-21 14:46:13', 'pendente', '550.00', 5),
(22, 20, '2024-11-21 14:46:55', 'pendente', '120.00', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `imagem_url` varchar(255) DEFAULT NULL,
  `marca` varchar(50) NOT NULL,
  `tamanho` varchar(10) DEFAULT NULL,
  `cor` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `estoque`, `categoria`, `imagem_url`, `marca`, `tamanho`, `cor`) VALUES
(3, 'Camiseta', 'Fabricada com tecido jersey macio, a camiseta Nike SB é uma peça essencial do skate com caimento solto e o logotipo clássico no peito.\r\n\r\n\r\nDetalhes do produto\r\n\r\nCaimento solto para uma sensação espaçosa\r\nGola com nervuras\r\n80–100% algodão e 0–20% poliéster\r\nAs porcentagens dos materiais podem variar. Verifique a etiqueta quanto ao conteúdo real.\r\nLavagem à máquina\r\nImportado\r\n\r\nTamanho & Caimento\r\n\r\nCaimento solto para uma sensação espaçosa', '120.00', 24, 'roupa', 'uploads/CamisetaNike.jfif', 'Nike', 'GG', 'Preto'),
(5, 'Air Max Plus', 'O Air Max Plus proporciona uma experiência Nike Air ajustada que oferece estabilidade premium e amortecimento inacreditável. O material sintético na parte de cima com tela leve proporciona respirabilidade e durabilidade. Estrutura de plástico e arco no mediopé proporcionam sensação de suporte.', '999.00', 106, 'tenis', 'uploads/AirMaxPlus.jfif', 'Nike', '40', 'Preto'),
(6, 'Air Max Plus', 'O Air Max Plus proporciona uma experiência Nike Air ajustada que oferece estabilidade premium e amortecimento inacreditável. O material sintético na parte de cima com tela leve proporciona respirabilidade e durabilidade. Estrutura de plástico e arco no mediopé proporcionam sensação de suporte.', '1000.00', 198, 'tenis', 'uploads/AirMaxPlus.jfif', 'Nike', '39', 'Preto'),
(7, 'Puma Suede XL', 'O clássico dos clássicos, o tênis PUMA Suede vem definindo o estilo de lendas do esporte e breakdance desde 1968 e, até aqui, é o mais famoso de todos os modelos PUMA, merecendo seu lugar no hall da fama. O PUMA Suede Classic se tornou conhecido em todos os campos da cultura, desde o icônico gesto silencioso de Tommie Smith nos Jogos Olímpicos de 1968 até o pico da cultura hip hop dos anos 90 e o estilo de rua de Nova York. Quando o assunto é cultura urbana, o Suede ganha o título de primeiro tênis do universo dos B-Boys e B-Girls. Sempre clássico, o PUMA Suede é todo em camurça suave e estilo inspirado no esporte, permanecendo até hoje o ícone mais épico dos modelos PUMA.', '550.00', 198, 'tenis', 'uploads/PumaSuedeXl.jfif', 'Puma', '42', 'Preto e Branco'),
(8, 'Tênis Nike Air Force 1 \"07 Masculino', 'O brilho perdura no Nike Air Force 1 ’07, o tênis original do basquete que dá um toque de inovação naquilo que você conhece bem: sobreposições costuradas e duráveis, acabamentos simples e a quantidade perfeita de brilho para fazer você se destacar.Estreando em 1982, o AF1 foi o primeiro tênis de basquete a abrigar o Nike Air, revolucionando o jogo e ganhando tração rapidamente em todo o mundo. Hoje, o Air Force 1 permanece fiel às suas raízes com o mesmo amortecimento macio e elástico que mudou a história dos sneakers.', '750.00', 199, 'tenis', 'uploads/AirForce1.jfif', 'Nike', '42', 'Branco'),
(9, 'Camisa do Real Madrid I 24/25 adidas', 'AEROREADY: Desenvolvida para proporcionar respirabilidade e absorção do suor para comodidade duradoura.', '250.00', 39, 'roupa', 'uploads/CamisaRealMadri.jfif', 'Adidas', 'G', 'Branca'),
(10, 'Camisa Nike Brasil I 2022/23 Jogador Masculina', 'A Coleção da Seleção Brasileira de 2022 combina a icônica estampa da onça-pintada com design inovador que mantém seu corpo seco mesmo no auge da empolgação. Uma homenagem ao Brasil e ao seu povo, esta coleção foi feita para mostrar a sua garra.\r\n\r\n#VesteAGarra\r\n\r\n\r\nÉ a vontade de seguir em frente.\r\nÉ sangue no olho.\r\nÉ pressão. Dentro e fora do campo.\r\nÉ driblar, pedalar, rabiscar, lutar.\r\nNo país que não desiste nunca, garra é a segunda língua.\r\nÉ acreditar até o último segundo.\r\nÉ coletivo. Representa mais de 210 milhões de brasileiros.\r\nÉ a nossa Garra.\r\nA Camisa\r\n\r\nComo outras camisas da nossa coleção Match, ela combina detalhes de design leves com tecido que seca rápido para ajudar os maiores craques do mundo a se manterem secos e confortáveis dentro do campo.\r\n\r\n\r\nFeita para Ventilação Otimizada\r\n\r\nA tecnologia Nike Dri-FIT ADV combina tecido que absorve o suor com engenharia e recursos avançados para ajudar a manter você seco e confortável. Desenvolvido a partir de testes com atletas, o tecido com perfurações nas áreas de alto aquecimento ajuda você a manter-se fresco quando o jogo esquentar.', '340.00', 23, 'roupa', 'uploads/CamisaBrasil.jfif', 'Nike', 'G', 'Amarelo'),
(11, 'Air Max Plus III', 'O Nike Air Max Plus III proporciona uma experiência Nike Air ajustada que oferece estabilidade premium e amortecimento inacreditável. Trazendo a descontraída vida praiana para a cidade, o arco de sustentação no médio pé se inspira na cauda de uma baleia.', '1200.00', 29, 'tenis', 'uploads/download.jfif', 'Nike', '41', 'Preto e Branco');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `tipo_usuario` enum('cliente','funcionario','administrador') NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `sexo` enum('masculino','feminino','outro') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `endereco`, `telefone`, `tipo_usuario`, `cpf`, `sexo`) VALUES
(5, 'João Victor', 'morais@gmail.com', 'JJRm1205', NULL, '(12) 31231-2312', 'administrador', '123.123.123-13', 'masculino'),
(20, 'Bruno\r\n', 'bruno2@gmail.com', 'JJRm1205', NULL, '(12) 31231-2312', 'cliente', '123.123.123-12', 'masculino'),
(24, 'Bruno', 'bruno@gmail.com', 'JJRm1205', NULL, '(12) 31231-2312', 'funcionario', '123.123.123-12', 'masculino'),
(26, 'Vinicius', 'vinicius@gmail.com', 'JJRm1205', NULL, '(12) 31231-2312', 'administrador', '123.123.123-12', 'masculino'),
(27, 'Thaua', 'brandao@hotmail.com', 'Thauabrandao123', NULL, '(73) 99813-9123', 'funcionario', '088.288.991-55', 'feminino');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `enderecos_entrega`
--
ALTER TABLE `enderecos_entrega`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_endereco_entrega` (`endereco_entrega_id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `enderecos_entrega`
--
ALTER TABLE `enderecos_entrega`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `enderecos_entrega`
--
ALTER TABLE `enderecos_entrega`
  ADD CONSTRAINT `enderecos_entrega_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Limitadores para a tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD CONSTRAINT `funcionarios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Limitadores para a tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Limitadores para a tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_endereco_entrega` FOREIGN KEY (`endereco_entrega_id`) REFERENCES `enderecos_entrega` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
