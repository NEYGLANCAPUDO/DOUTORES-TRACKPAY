CREATE DATABASE IF NOT EXISTS trackPay;
USE trackPay;

CREATE TABLE user (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(100) NOT NULL
);

CREATE TABLE bill (
    id_conta INT AUTO_INCREMENT PRIMARY KEY,
    iduser INT NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    valor FLOAT NOT NULL,
    importancia VARCHAR(3) DEFAULT '3',
    FOREIGN KEY (iduser) REFERENCES user(id_usuario)   
);

CREATE TABLE income (
    id_renda INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(100) NOT NULL,
    valor FLOAT NOT NULL,
    data_recebimento DATE NOT NULL,
    idUSUARIO INT NOT NULL,
    FOREIGN KEY (idUSUARIO) REFERENCES user(id_usuario)
);

CREATE TABLE fixedbill (
    id_conta_fixa INT AUTO_INCREMENT PRIMARY KEY,
    vencimento DATE NOT NULL,
    idbill INT NOT NULL,
    FOREIGN KEY (idbill) REFERENCES bill(id_conta)
);

CREATE TABLE variablebill (
    id_conta_variavel INT AUTO_INCREMENT PRIMARY KEY,
    data_gasto DATE NOT NULL,
    idbill INT NOT NULL,
    FOREIGN KEY (idbill) REFERENCES bill(id_conta)
);