-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: shared-mysql:3306
-- Tempo de geração: 02/05/2023 às 21:01
-- Versão do servidor: 5.6.51
-- Versão do PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `burnbase`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `name` varchar(60) DEFAULT NULL,
  `username` varchar(10) DEFAULT NULL,
  `password` char(40) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `permissions` text,
  `type` char(1) NOT NULL DEFAULT 'N',
  `active` char(1) NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `admin_users`
--

INSERT INTO `admin_users` (`id`, `created`, `modified`, `name`, `username`, `password`, `email`, `permissions`, `type`, `active`) VALUES
(1, '2011-10-20 20:07:22', '2017-05-19 17:48:34', 'Webmaster', 'burnweb', 'b2fd1a0a7bf0b5aa4462979ba7c3120d103e08e6', 'contato@burnweb.com.br', '', 'A', 'Y'),
(2, '2017-06-14 17:17:32', '2017-08-01 15:28:21', 'Administrador', 'admin', 'b2fd1a0a7bf0b5aa4462979ba7c3120d103e08e6', 'adm@burnweb.com.br', '', 'A', 'Y');

-- --------------------------------------------------------

--
-- Estrutura para tabela `audits`
--

DROP TABLE IF EXISTS `audits`;
CREATE TABLE `audits` (
  `id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(64) NOT NULL,
  `name` text,
  `entity` varchar(32) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `details` longtext,
  `session_id` varchar(255) NOT NULL,
  `ip` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `audits`
--

INSERT INTO `audits` (`id`, `created`, `user_id`, `action`, `name`, `entity`, `entity_id`, `details`, `session_id`, `ip`) VALUES
(1, '2023-04-28 16:50:54', 1, 'access', 'Webmaster', NULL, NULL, NULL, '3cb2858b8d93579ceb76c14d0050dbeb', '172.18.0.1'),
(2, '2023-04-28 16:50:59', 1, 'access', 'Webmaster', NULL, NULL, NULL, '3cb2858b8d93579ceb76c14d0050dbeb', '172.18.0.1'),
(3, '2023-04-28 16:52:12', 1, 'access', 'Webmaster', NULL, NULL, NULL, '3cb2858b8d93579ceb76c14d0050dbeb', '172.18.0.1'),
(4, '2023-05-02 16:12:33', 1, 'access', 'Webmaster', NULL, NULL, NULL, '0ecf741f61b7ddede0d7e4dd24d523ba', '172.18.0.1'),
(5, '2023-05-02 16:13:59', 1, 'access', 'Webmaster', NULL, NULL, NULL, '0ecf741f61b7ddede0d7e4dd24d523ba', '172.18.0.1'),
(6, '2023-05-02 16:14:39', 1, 'access', 'Webmaster', NULL, NULL, NULL, '0ecf741f61b7ddede0d7e4dd24d523ba', '172.18.0.1'),
(7, '2023-05-02 17:23:56', 1, 'add', 'Criação: Teste de notícia', 'Noticia', 1, NULL, '68db8df857b771a1b239dcc807cb6a9c', '172.18.0.1'),
(8, '2023-05-02 17:24:51', 1, 'add', 'Criação: Teste de notícia', 'Noticia', 1, NULL, '68db8df857b771a1b239dcc807cb6a9c', '172.18.0.1'),
(9, '2023-05-02 17:28:20', 1, 'delete', 'Exclusão: Teste de notícia', 'Noticia', 1, NULL, 'eebef5e97cbd5f98758c2db7f80e1878', '172.18.0.1'),
(10, '2023-05-02 17:28:50', 1, 'add', 'Criação: Teste de notícia', 'Noticia', 2, NULL, 'eebef5e97cbd5f98758c2db7f80e1878', '172.18.0.1'),
(11, '2023-05-02 17:29:23', 1, 'edit', 'Edição: Teste de notícia OK', 'Noticia', 2, '{\"prev\":{\"name\":\"Teste de not\\u00edcia\"}}', 'eebef5e97cbd5f98758c2db7f80e1878', '172.18.0.1'),
(12, '2023-05-02 17:32:41', 1, 'edit', 'Edição: Teste de notícia OK', 'Noticia', 2, '{\"prev\":{\"texto\":\"<p>Teste de not&iacute;cia&nbsp;<strong>Teste<\\/strong> de not&iacute;cia&nbsp;Teste de not&iacute;cia<\\/p>\\r\\n\",\"img_content\":\"[]\"}}', '546c493f7f5c06d7e4cacf6b4c5c3d41', '172.18.0.1'),
(13, '2023-05-02 17:33:01', 1, 'edit', 'Edição: Teste de notícia OK', 'Noticia', 2, '{\"prev\":{\"texto\":\"<p>Teste de not&iacute;cia&nbsp;<strong>Teste<\\/strong> de not&iacute;cia&nbsp;Teste de not&iacute;cia<img alt=\\\"\\\" height=\\\"1024\\\" src=\\\"\\/burnbase\\/beta\\/webroot\\/ckeditor\\/ckfinder\\/core\\/connector\\/php\\/connector.phpfiles\\/teste_burn.jpg\\\" width=\\\"797\\\" \\/><\\/p>\\r\\n\",\"img_content\":\"[\\\"\\\\\\/burnbase\\\\\\/beta\\\\\\/webroot\\\\\\/ckeditor\\\\\\/ckfinder\\\\\\/core\\\\\\/connector\\\\\\/php\\\\\\/connector.phpfiles\\\\\\/teste_burn.jpg\\\"]\"}}', '546c493f7f5c06d7e4cacf6b4c5c3d41', '172.18.0.1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cake_sessions`
--

DROP TABLE IF EXISTS `cake_sessions`;
CREATE TABLE `cake_sessions` (
  `id` varchar(64) NOT NULL DEFAULT '',
  `data` text,
  `expires` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `cake_sessions`
--

INSERT INTO `cake_sessions` (`id`, `data`, `expires`) VALUES
('082efe0794904c146ecb2bf0da1a4415', 'Config|a:3:{s:9:\"userAgent\";s:32:\"50aa85b4710260574da8f13a0bcc0727\";s:4:\"time\";i:1683666065;s:9:\"countdown\";i:2;}Message|a:0:{}Auth|a:1:{s:9:\"AdminUser\";a:10:{s:2:\"id\";s:1:\"1\";s:7:\"created\";s:19:\"2011-10-20 20:07:22\";s:8:\"modified\";s:19:\"2017-05-19 17:48:34\";s:4:\"name\";s:9:\"Webmaster\";s:8:\"username\";s:7:\"burnweb\";s:5:\"email\";s:22:\"contato@burnweb.com.br\";s:11:\"permissions\";s:0:\"\";s:4:\"type\";s:1:\"A\";s:6:\"active\";s:1:\"Y\";s:12:\"logoutAction\";s:22:\"/admin/cmanager/logout\";}}Referer|a:4:{s:21:\"noticias_admin_widget\";s:51:\"http://dev.lucas/burnbase/beta/admin/cmanager/login\";s:20:\"cmanager_admin_users\";s:36:\"http://dev.lucas/burnbase/beta/admin\";s:20:\"noticias_admin_index\";s:51:\"http://dev.lucas/burnbase/beta/admin/cmanager/users\";s:19:\"noticias_admin_edit\";s:45:\"http://dev.lucas/burnbase/beta/admin/noticias\";}FreezeState|a:1:{s:7:\"Noticia\";a:1:{i:2;a:7:{s:2:\"id\";s:1:\"2\";s:4:\"data\";s:19:\"2023-05-02 17:30:00\";s:4:\"name\";s:20:\"Teste de notícia OK\";s:5:\"texto\";s:107:\"<p>Teste de not&iacute;cia&nbsp;<strong>Teste</strong> de not&iacute;cia&nbsp;Teste de not&iacute;cia</p>\r\n\";s:11:\"img_content\";s:2:\"[]\";s:5:\"ativo\";s:1:\"S\";s:4:\"slug\";s:21:\"teste-de-noticia-ok-2\";}}}', 1683666066);

-- --------------------------------------------------------

--
-- Estrutura para tabela `configurations`
--

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE `configurations` (
  `id` int(6) NOT NULL,
  `variable` varchar(60) NOT NULL,
  `label` varchar(120) NOT NULL,
  `value` text,
  `json` int(2) NOT NULL DEFAULT '0',
  `editable` enum('N','S') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `configurations`
--

INSERT INTO `configurations` (`id`, `variable`, `label`, `value`, `json`, `editable`) VALUES
(1, 'title', 'Título da Página (SEO)', 'Burn Base', 0, 'S'),
(2, 'description', 'Descrição do Site (SEO)', '', 0, 'S'),
(3, 'keywords', 'Palavras-Chave (SEO)', '', 0, 'S'),
(4, 'domain', 'Domínio Principal', 'www.burnweb.com.br', 0, 'S'),
(5, 'email_contato', 'E-mail para receber o contato', 'contato@burnweb.com.br', 0, 'S'),
(6, 'name', 'Nome do Projeto', 'Burn Base', 0, 'S'),
(7, 'subtitle', 'Subtitulo da Página', '', 0, 'S'),
(8, 'email', 'E-mail de exibição', 'contato@burnweb.com.br', 0, 'S'),
(9, 'script-ga', 'Tag de Script do Google Analytics', '', 0, 'S'),
(10, 'email_curriculo', 'E-mail para receber o Trabalhe Conosco', 'contato@burnweb.com.br', 0, 'S'),
(11, 'fone', 'Telefone Principal', '(48) 3438-7938', 0, 'S');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estados`
--

DROP TABLE IF EXISTS `estados`;
CREATE TABLE `estados` (
  `uf` varchar(3) NOT NULL,
  `estado` varchar(60) NOT NULL,
  `area` int(6) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `estados`
--

INSERT INTO `estados` (`uf`, `estado`, `area`) VALUES
('AC', 'Acre', 5),
('AL', 'Alagoas', 4),
('AM', 'Amazonas', 5),
('AP', 'Amapá', 5),
('BA', 'Bahia', 4),
('CE', 'Ceará', 4),
('DF', 'Distrito Federal', 3),
('ES', 'Espírito Santo', 2),
('GO', 'Goiás', 3),
('MA', 'Maranhão', 4),
('MG', 'Minas Gerais', 2),
('MS', 'Mato Grosso do Sul', 3),
('MT', 'Mato Grosso', 3),
('PA', 'Pará', 5),
('PB', 'Paraíba', 4),
('PE', 'Pernambuco', 4),
('PI', 'Piauí', 4),
('PR', 'Paraná', 1),
('RJ', 'Rio de Janeiro', 2),
('RN', 'Rio Grande do Norte', 4),
('RO', 'Rondônia', 5),
('RR', 'Roraima', 5),
('RS', 'Rio Grande do Sul', 1),
('SC', 'Santa Catarina', 1),
('SE', 'Sergipe', 4),
('SP', 'São Paulo', 2),
('TO', 'Tocantins', 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `external_contents`
--

DROP TABLE IF EXISTS `external_contents`;
CREATE TABLE `external_contents` (
  `id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `method` char(6) NOT NULL DEFAULT 'GET',
  `url` varchar(255) NOT NULL,
  `data` mediumtext,
  `response` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura para tabela `noticias`
--

DROP TABLE IF EXISTS `noticias`;
CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `texto` longtext NOT NULL,
  `img_content` text,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `noticias`
--

INSERT INTO `noticias` (`id`, `created`, `modified`, `data`, `name`, `texto`, `img_content`, `ativo`) VALUES
(2, '2023-05-02 17:28:50', '2023-05-02 17:33:01', '2023-05-02 17:30:00', 'Teste de notícia OK', '<p>Teste de not&iacute;cia&nbsp;<strong>Teste</strong> de not&iacute;cia&nbsp;Teste de not&iacute;cia</p>\r\n', '[]', 'S');

-- --------------------------------------------------------

--
-- Estrutura para tabela `noticia_fotos`
--

DROP TABLE IF EXISTS `noticia_fotos`;
CREATE TABLE `noticia_fotos` (
  `id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `noticia_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `img` varchar(128) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `default` char(1) NOT NULL DEFAULT 'N',
  `ativo` char(1) NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `noticia_fotos`
--

INSERT INTO `noticia_fotos` (`id`, `created`, `modified`, `noticia_id`, `name`, `img`, `order`, `default`, `ativo`) VALUES
(2, '2023-05-02 17:28:50', '2023-05-02 17:28:50', 2, NULL, 'teste-burn_2.jpg', 0, 'Y', 'Y'),
(3, '2023-05-02 17:29:23', '2023-05-02 17:29:23', 2, NULL, 'teste-burn_3.jpg', 0, 'N', 'Y');

-- --------------------------------------------------------

--
-- Estrutura para tabela `noticia_tags`
--

DROP TABLE IF EXISTS `noticia_tags`;
CREATE TABLE `noticia_tags` (
  `id` int(11) NOT NULL,
  `noticia_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `noticia_tags`
--

INSERT INTO `noticia_tags` (`id`, `noticia_id`, `name`) VALUES
(9, 2, 'teste1'),
(10, 2, 'teste2');

-- --------------------------------------------------------

--
-- Estrutura para tabela `paginas`
--

DROP TABLE IF EXISTS `paginas`;
CREATE TABLE `paginas` (
  `id` int(10) UNSIGNED NOT NULL,
  `pin` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `corpo` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagina_fotos`
--

DROP TABLE IF EXISTS `pagina_fotos`;
CREATE TABLE `pagina_fotos` (
  `id` int(6) UNSIGNED NOT NULL,
  `pagina_id` int(6) UNSIGNED NOT NULL,
  `legenda` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indexes` (`username`,`type`),
  ADD KEY `active` (`active`);

--
-- Índices de tabela `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `entity` (`entity`,`entity_id`),
  ADD KEY `action` (`action`);

--
-- Índices de tabela `cake_sessions`
--
ALTER TABLE `cake_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variable` (`variable`),
  ADD KEY `label` (`label`);

--
-- Índices de tabela `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`uf`);

--
-- Índices de tabela `external_contents`
--
ALTER TABLE `external_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ec_url` (`url`(191)),
  ADD KEY `ec_expires` (`expires`),
  ADD KEY `ec_method` (`method`),
  ADD KEY `ec_q` (`method`,`url`(191));

--
-- Índices de tabela `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `titulo` (`name`(191)),
  ADD KEY `data` (`data`),
  ADD KEY `ativo` (`ativo`);

--
-- Índices de tabela `noticia_fotos`
--
ALTER TABLE `noticia_fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `default` (`default`),
  ADD KEY `ativo` (`ativo`),
  ADD KEY `order` (`order`),
  ADD KEY `noticia_id` (`noticia_id`) USING BTREE,
  ADD KEY `noticia_ativa` (`noticia_id`,`ativo`) USING BTREE;

--
-- Índices de tabela `noticia_tags`
--
ALTER TABLE `noticia_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `noticia_id` (`noticia_id`),
  ADD KEY `name` (`name`);

--
-- Índices de tabela `paginas`
--
ALTER TABLE `paginas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pin` (`pin`),
  ADD KEY `name` (`name`(191));

--
-- Índices de tabela `pagina_fotos`
--
ALTER TABLE `pagina_fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pagina_id` (`pagina_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `audits`
--
ALTER TABLE `audits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `external_contents`
--
ALTER TABLE `external_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `noticia_fotos`
--
ALTER TABLE `noticia_fotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `noticia_tags`
--
ALTER TABLE `noticia_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `paginas`
--
ALTER TABLE `paginas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
