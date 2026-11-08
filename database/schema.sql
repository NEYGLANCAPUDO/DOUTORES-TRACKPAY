CREATE DATABASE IF NOT EXISTS `trackpay`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `trackpay`;
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `perfil` ENUM('operador','supervisor','administrador') NOT NULL DEFAULT 'operador',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `bill` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `id_category` INT DEFAULT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `due_date` TINYINT UNSIGNED NOT NULL,
  `description` VARCHAR(100) NOT NULL,
  `importance` VARCHAR(3) DEFAULT '3',
  `paid` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bill_user` (`user_id`),
  KEY `idx_bill_category` (`id_category`),
  CONSTRAINT `fk_bill_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `fk_bill_category` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `fixedbill` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `vencimento` DATE NOT NULL,
  `idbill` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_fixed_bill` (`idbill`),
  CONSTRAINT `fk_fixed_bill` FOREIGN KEY (`idbill`) REFERENCES `bill` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `variablebill` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `data_gasto` DATE NOT NULL,
  `idbill` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_variable_bill` (`idbill`),
  CONSTRAINT `fk_variable_bill` FOREIGN KEY (`idbill`) REFERENCES `bill` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `income` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(100) NOT NULL,
  `valor` DECIMAL(12,2) NOT NULL,
  `data_recebimento` DATE NOT NULL,
  `idUSUARIO` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_income_user` (`idUSUARIO`),
  CONSTRAINT `fk_income_user` FOREIGN KEY (`idUSUARIO`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `category` (`id`, `nome`) VALUES
 (1, 'Moradia'), (2, 'Alimentação'), (3, 'Transporte'),
 (4, 'Saúde'), (5, 'Educação'), (6, 'Outros');


CREATE TABLE IF NOT EXISTS `bill_month_payment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `bill_id` INT NOT NULL,
  `month_ref` CHAR(7) NOT NULL,
  `paid` TINYINT(1) NOT NULL DEFAULT 0,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_bill_month` (`bill_id`,`month_ref`),
  CONSTRAINT `fk_bill_month_bill` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
