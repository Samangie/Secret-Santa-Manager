CREATE DATABASE `secret-santa-manager`
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE `secret-santa-manager`;

CREATE TABLE `user` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(30) NOT NULL,
    `password` VARCHAR(40) NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `role` TINYINT NOT NULL
    );

CREATE TABLE `campaign` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `startdate` DATE
    );

CREATE TABLE `user_campaign` (
    `user_id` INT,
    `campaign_id` INT,
    CONSTRAINT FK_User FOREIGN KEY (`user_id`)
    REFERENCES user(`id`),
    CONSTRAINT FK_Campaign FOREIGN KEY (`campaign_id`)
    REFERENCES campaign(`id`)
    );
