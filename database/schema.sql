CREATE DATABASE IF NOT EXISTS trackpay CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE trackpay;

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET NAMES utf8mb4;

-- Esquema unificado: usa os nomes de colunas do banco do "TrackPay - site foda"
-- para que o painel de contas funcione, preservando as funções de usuário do DOUTORES-TRACKPAY.

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `senha` VARCHAR(100) NOT NULL,
  `perfil` ENUM('operador','supervisor','administrador') NOT NULL DEFAULT 'operador',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `bill` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `id_category` INT(11) DEFAULT NULL,
  `price` FLOAT NOT NULL,
  `due_date` TINYINT(2) NOT NULL,
  `description` VARCHAR(100) NOT NULL,
  `importance` VARCHAR(3) DEFAULT '3',
  `paid` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bill_user` (`user_id`),
  KEY `idx_bill_category` (`id_category`),
  CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `bill_ibfk_2` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `fixedbill` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `vencimento` DATE NOT NULL,
  `idbill` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idbill_unique` (`idbill`),
  CONSTRAINT `fixedbill_ibfk_1` FOREIGN KEY (`idbill`) REFERENCES `bill` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `variablebill` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `data_gasto` DATE NOT NULL,
  `idbill` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idbill_unique` (`idbill`),
  CONSTRAINT `variablebill_ibfk_1` FOREIGN KEY (`idbill`) REFERENCES `bill` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `income` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(100) NOT NULL,
  `valor` FLOAT NOT NULL,
  `data_recebimento` DATE NOT NULL,
  `idUSUARIO` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_income_user` (`idUSUARIO`),
  CONSTRAINT `income_ibfk_1` FOREIGN KEY (`idUSUARIO`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `category` (`id`, `nome`) VALUES (0, 'Sem categoria');
