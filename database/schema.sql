SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `user` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `perfil` ENUM('operador','supervisor','administrador') NOT NULL DEFAULT 'operador',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bill` (
  `id_conta` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `idcategoria` int(11) DEFAULT NULL,
  `descricao` varchar(100) NOT NULL,
  `valor` float NOT NULL,
  `importancia` varchar(3) DEFAULT '3',
  `pago` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `fixedbill` (
  `id_conta_fixa` int(11) NOT NULL,
  `vencimento` date NOT NULL,
  `idbill` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `variablebill` (
  `id_conta_variavel` int(11) NOT NULL,
  `data_gasto` date NOT NULL,
  `idbill` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `income` (
  `id_renda` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `valor` float NOT NULL,
  `data_recebimento` date NOT NULL,
  `idUSUARIO` int(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `user`
  ADD PRIMARY KEY (`id_usuario`);

ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

ALTER TABLE `bill`
  ADD PRIMARY KEY (`id_conta`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `idcategoria` (`idcategoria`);


ALTER TABLE `fixedbill`
  ADD PRIMARY KEY (`id_conta_fixa`),
  ADD UNIQUE KEY `idbill_unique` (`idbill`);

ALTER TABLE `variablebill`
  ADD PRIMARY KEY (`id_conta_variavel`),
  ADD UNIQUE KEY `idbill_unique` (`idbill`);

ALTER TABLE `income`
  ADD PRIMARY KEY (`id_renda`),
  ADD KEY `idUSUARIO` (`idUSUARIO`);



ALTER TABLE `user`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `bill`
  MODIFY `id_conta` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `fixedbill`
  MODIFY `id_conta_fixa` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `variablebill`
  MODIFY `id_conta_variavel` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `income`
  MODIFY `id_renda` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`id_usuario`),
  ADD CONSTRAINT `bill_ibfk_2` FOREIGN KEY (`idcategoria`) REFERENCES `categoria` (`id_categoria`);

ALTER TABLE `fixedbill`
  ADD CONSTRAINT `fixedbill_ibfk_1` FOREIGN KEY (`idbill`) REFERENCES `bill` (`id_conta`);

ALTER TABLE `variablebill`
  ADD CONSTRAINT `variablebill_ibfk_1` FOREIGN KEY (`idbill`) REFERENCES `bill` (`id_conta`);

ALTER TABLE `income`
  ADD CONSTRAINT `income_ibfk_1` FOREIGN KEY (`idUSUARIO`) REFERENCES `user` (`id_usuario`);

COMMIT;
