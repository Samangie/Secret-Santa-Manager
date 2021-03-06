CREATE DATABASE IF NOT EXISTS `secret-santa-manager`
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
    `title` VARCHAR(30),
    `startdate` DATE,
    `isAssigned` TINYINT DEFAULT 0
    );

CREATE TABLE `user_campaign` (
    `user_id` INT,
    `campaign_id` INT,
    CONSTRAINT FK_User FOREIGN KEY (`user_id`)
    REFERENCES user(`id`) ON DELETE CASCADE,
    CONSTRAINT FK_Campaign FOREIGN KEY (`campaign_id`)
    REFERENCES campaign(`id`) ON DELETE CASCADE
    );

CREATE TABLE `assigned_user` (
    `campaign_id` INT,
    `santa_id` INT,
    `donee_id` INT,
    CONSTRAINT FK_CampaignAssigned FOREIGN KEY (`campaign_id`)
    REFERENCES campaign(`id`) ON DELETE CASCADE
    );