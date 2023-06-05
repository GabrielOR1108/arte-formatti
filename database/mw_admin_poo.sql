-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11-Abr-2023 às 20:24
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
-- Banco de dados: `mw_admin_poo`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `mw_cookies`
--

CREATE TABLE `mw_cookies` (
  `id` int(11) NOT NULL,
  `title` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `text` mediumtext COLLATE latin1_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncar tabela antes do insert `mw_cookies`
--

TRUNCATE TABLE `mw_cookies`;
--
-- Extraindo dados da tabela `mw_cookies`
--

INSERT INTO `mw_cookies` (`id`, `title`, `text`, `created_at`, `updated_at`) VALUES
(1, 'Este site usa cookies.', 'Usamos cookies para aprimorar a navegabilidade, analisar o trafego do site e otimizar sua experiência, mas não armazenamos ou tratamos  dados pessoais. Ao aceitar nosso uso de cookies, seus dados serão agregados a uma base anonimizada.', '2022-09-05 14:33:42', '2022-11-21 10:13:49');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mw_email_layout`
--

CREATE TABLE `mw_email_layout` (
  `id` int(11) NOT NULL,
  `top` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `bottom` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncar tabela antes do insert `mw_email_layout`
--

TRUNCATE TABLE `mw_email_layout`;
--
-- Extraindo dados da tabela `mw_email_layout`
--

INSERT INTO `mw_email_layout` (`id`, `top`, `bottom`, `created_at`, `updated_at`) VALUES
(1, 'top.png', 'bottom.png', '2022-12-08 15:08:15', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mw_group_menu`
--

CREATE TABLE `mw_group_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `icon` varchar(120) COLLATE latin1_general_ci NOT NULL DEFAULT 'bi bi-exclamation-triangle-fill',
  `priority` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncar tabela antes do insert `mw_group_menu`
--

TRUNCATE TABLE `mw_group_menu`;
-- --------------------------------------------------------

--
-- Estrutura da tabela `mw_menu`
--

CREATE TABLE `mw_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `mw_group_menu` int(11) NOT NULL,
  `icon` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `url` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncar tabela antes do insert `mw_menu`
--

TRUNCATE TABLE `mw_menu`;
-- --------------------------------------------------------

--
-- Estrutura da tabela `mw_metatags`
--

CREATE TABLE `mw_metatags` (
  `id` int(11) NOT NULL,
  `description` mediumtext COLLATE latin1_general_ci DEFAULT NULL,
  `keywords` mediumtext COLLATE latin1_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncar tabela antes do insert `mw_metatags`
--

TRUNCATE TABLE `mw_metatags`;
--
-- Extraindo dados da tabela `mw_metatags`
--

INSERT INTO `mw_metatags` (`id`, `description`, `keywords`, `created_at`, `updated_at`) VALUES
(1, 'Meta Descrição', 'Meta keywords', '2022-11-21 13:37:56', '2022-11-21 13:42:50');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mw_recipients`
--

CREATE TABLE `mw_recipients` (
  `id` int(11) NOT NULL,
  `name` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `contact` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncar tabela antes do insert `mw_recipients`
--

TRUNCATE TABLE `mw_recipients`;
--
-- Extraindo dados da tabela `mw_recipients`
--

INSERT INTO `mw_recipients` (`id`, `name`, `email`, `contact`, `created_at`, `updated_at`) VALUES
(1, 'Vitor', 'vitor@makeweb.com.br', 1, '2022-12-08 17:41:58', '2022-12-08 17:42:03'),
(2, 'Makeweb', 'trafegomakeweb@gmail.com', 1, '2022-12-08 17:56:58', '2022-12-09 08:54:53');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mw_recoveries`
--

CREATE TABLE `mw_recoveries` (
  `id` int(11) NOT NULL,
  `email` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `hash` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `requested_at` datetime NOT NULL DEFAULT current_timestamp(),
  `used_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncar tabela antes do insert `mw_recoveries`
--

TRUNCATE TABLE `mw_recoveries`;
-- --------------------------------------------------------

--
-- Estrutura da tabela `mw_smtp_config`
--

CREATE TABLE `mw_smtp_config` (
  `id` int(11) NOT NULL,
  `host` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `user` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `pass` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `auth` enum('SSL','TLS') COLLATE latin1_general_ci NOT NULL,
  `port` enum('25','465','587') COLLATE latin1_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncar tabela antes do insert `mw_smtp_config`
--

TRUNCATE TABLE `mw_smtp_config`;
--
-- Extraindo dados da tabela `mw_smtp_config`
--

INSERT INTO `mw_smtp_config` (`id`, `host`, `user`, `pass`, `name`, `auth`, `port`, `created_at`, `updated_at`) VALUES
(1, 'mail.desenvolvimentomw.com.br', 'lucas@desenvolvimentomw.com.br', 'TWFrZSNsdWNAMjAxOSo=', 'Makeweb', 'TLS', '587', '2022-09-08 11:22:56', '2022-11-22 09:21:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mw_users`
--

CREATE TABLE `mw_users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `last_name` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `image` varchar(220) COLLATE latin1_general_ci DEFAULT NULL,
  `level` int(11) NOT NULL,
  `favorites` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`favorites`)),
  `last_login` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncar tabela antes do insert `mw_users`
--

TRUNCATE TABLE `mw_users`;
--
-- Extraindo dados da tabela `mw_users`
--

INSERT INTO `mw_users` (`id`, `first_name`, `last_name`, `email`, `password`, `image`, `level`, `favorites`, `last_login`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Vitor', 'Bizarra', 'vitor@makeweb.com.br', '$2y$10$vgTZz3oqTEMDB7Ce7BbLA.r8Zy7/BoJDutSsyM11Wf8WAVamZQ.Wi', '16812330076435946fa296c.webp', 1, '[]', '2023-04-11 15:23:13', 1, '2022-09-05 14:33:43', '2023-04-11 15:23:13');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mw_user_level`
--

CREATE TABLE `mw_user_level` (
  `id` int(11) NOT NULL,
  `level` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncar tabela antes do insert `mw_user_level`
--

TRUNCATE TABLE `mw_user_level`;
--
-- Extraindo dados da tabela `mw_user_level`
--

INSERT INTO `mw_user_level` (`id`, `level`, `active`) VALUES
(1, 'Admin', 1),
(2, 'Comum', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `mw_cookies`
--
ALTER TABLE `mw_cookies`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mw_email_layout`
--
ALTER TABLE `mw_email_layout`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mw_group_menu`
--
ALTER TABLE `mw_group_menu`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mw_menu`
--
ALTER TABLE `mw_menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `mw_group_menu` (`mw_group_menu`);

--
-- Índices para tabela `mw_metatags`
--
ALTER TABLE `mw_metatags`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mw_recipients`
--
ALTER TABLE `mw_recipients`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mw_recoveries`
--
ALTER TABLE `mw_recoveries`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mw_smtp_config`
--
ALTER TABLE `mw_smtp_config`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mw_users`
--
ALTER TABLE `mw_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`email`),
  ADD KEY `Mw_user_level` (`level`);

--
-- Índices para tabela `mw_user_level`
--
ALTER TABLE `mw_user_level`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `mw_cookies`
--
ALTER TABLE `mw_cookies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `mw_email_layout`
--
ALTER TABLE `mw_email_layout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `mw_group_menu`
--
ALTER TABLE `mw_group_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mw_menu`
--
ALTER TABLE `mw_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mw_metatags`
--
ALTER TABLE `mw_metatags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `mw_recipients`
--
ALTER TABLE `mw_recipients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `mw_recoveries`
--
ALTER TABLE `mw_recoveries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mw_smtp_config`
--
ALTER TABLE `mw_smtp_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `mw_users`
--
ALTER TABLE `mw_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `mw_user_level`
--
ALTER TABLE `mw_user_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `mw_menu`
--
ALTER TABLE `mw_menu`
  ADD CONSTRAINT `Mw_group_menu` FOREIGN KEY (`mw_group_menu`) REFERENCES `mw_group_menu` (`id`),
  ADD CONSTRAINT `mw_menu_ibfk_1` FOREIGN KEY (`mw_group_menu`) REFERENCES `mw_group_menu` (`id`);

--
-- Limitadores para a tabela `mw_users`
--
ALTER TABLE `mw_users`
  ADD CONSTRAINT `Mw_user_level` FOREIGN KEY (`level`) REFERENCES `mw_user_level` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
