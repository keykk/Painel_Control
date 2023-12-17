-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19-Set-2020 às 05:32
-- Versão do servidor: 10.4.14-MariaDB
-- versão do PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `painel`
--
CREATE DATABASE IF NOT EXISTS `painel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `painel`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `escrita`
--

CREATE TABLE `escrita` (
  `codigo` int(11) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `exibe_item_codigo` int(11) NOT NULL,
  `item_codigo` int(11) NOT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `exibe_item`
--

CREATE TABLE `exibe_item` (
  `codigo` int(11) NOT NULL,
  `perfil_codigo` int(11) DEFAULT NULL,
  `item_codigo` int(11) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `item`
--

CREATE TABLE `item` (
  `codigo` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `raiz` varchar(150) NOT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil_acesso`
--

CREATE TABLE `perfil_acesso` (
  `codigo` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `perfil_acesso`
--

INSERT INTO `perfil_acesso` (`codigo`, `titulo`, `descricao`, `data_cadastro`) VALUES
(1, 'Admin', 'Administradores', '2020-09-19 00:20:49'),
(2, 'Teste', 'Teste', '2020-09-19 00:29:55');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `codigo` int(11) NOT NULL,
  `login` varchar(100) NOT NULL,
  `senha` char(32) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `perfil_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`codigo`, `login`, `senha`, `nome`, `data_cadastro`, `perfil_codigo`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', '2020-09-19 00:21:03', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `escrita`
--
ALTER TABLE `escrita`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `exibe_item_codigo` (`exibe_item_codigo`),
  ADD KEY `item_codigo` (`item_codigo`);

--
-- Índices para tabela `exibe_item`
--
ALTER TABLE `exibe_item`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `item_codigo` (`item_codigo`),
  ADD KEY `perfil_codigo` (`perfil_codigo`);

--
-- Índices para tabela `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`codigo`),
  ADD UNIQUE KEY `titulo` (`titulo`),
  ADD UNIQUE KEY `raiz` (`raiz`);

--
-- Índices para tabela `perfil_acesso`
--
ALTER TABLE `perfil_acesso`
  ADD PRIMARY KEY (`codigo`),
  ADD UNIQUE KEY `titulo` (`titulo`);

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`codigo`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `perfil_codigo` (`perfil_codigo`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `escrita`
--
ALTER TABLE `escrita`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `exibe_item`
--
ALTER TABLE `exibe_item`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `item`
--
ALTER TABLE `item`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perfil_acesso`
--
ALTER TABLE `perfil_acesso`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `escrita`
--
ALTER TABLE `escrita`
  ADD CONSTRAINT `escrita_ibfk_1` FOREIGN KEY (`exibe_item_codigo`) REFERENCES `exibe_item` (`codigo`),
  ADD CONSTRAINT `escrita_ibfk_2` FOREIGN KEY (`item_codigo`) REFERENCES `item` (`codigo`);

--
-- Limitadores para a tabela `exibe_item`
--
ALTER TABLE `exibe_item`
  ADD CONSTRAINT `exibe_item_ibfk_1` FOREIGN KEY (`item_codigo`) REFERENCES `item` (`codigo`),
  ADD CONSTRAINT `exibe_item_ibfk_2` FOREIGN KEY (`perfil_codigo`) REFERENCES `perfil_acesso` (`codigo`);

--
-- Limitadores para a tabela `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`perfil_codigo`) REFERENCES `perfil_acesso` (`codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
