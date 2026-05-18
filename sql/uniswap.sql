-- phpMyAdmin SQL Dump — UniSwap (Full Seed)
-- Run this on a fresh database named `uniswap`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- -------------------------------------------------------
-- Database
-- -------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `uniswap` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `uniswap`;

-- -------------------------------------------------------
-- USER
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `USER` (
  `studentID`     char(10)     NOT NULL,
  `firstName`     varchar(50)  NOT NULL,
  `lastName`      varchar(50)  NOT NULL,
  `contactNumber` varchar(20)  DEFAULT NULL,
  `email`         varchar(100) DEFAULT NULL,
  `role`          varchar(10)  NOT NULL DEFAULT 'Student',
  `password`      varchar(255) DEFAULT NULL,
  PRIMARY KEY (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default test account  →  login: john.doe@example.com / password: password123
INSERT INTO `USER` (`studentID`,`firstName`,`lastName`,`contactNumber`,`email`,`role`,`password`) VALUES
('S123456789','John','Doe','09171234567','john.doe@example.com','Student','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('245427835','Lawrence','Lapig','09395446688','lapiglawrence0@gmail.com','Student','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('ADMIN00001','System','Administrator','00000000000','admin@uniswap.local','Admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- NOTE: The hash above is the bcrypt of "password" (Laravel default test hash)
-- All three accounts use password: password

-- -------------------------------------------------------
-- CATEGORY
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `CATEGORY` (
  `categoryID`   int(11)      NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(100) NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=8;

INSERT INTO `CATEGORY` (`categoryID`,`categoryName`) VALUES
(1,'Electronics'),
(2,'Books'),
(3,'Clothing'),
(4,'Lab Equipment'),
(5,'Tools & Drafting'),
(6,'Shoes & Accessories'),
(7,'Others');

-- -------------------------------------------------------
-- SELLER
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `SELLER` (
  `sellerID`      int(11)     NOT NULL AUTO_INCREMENT,
  `studentID`     char(10)    NOT NULL,
  `accountStatus` varchar(20) NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`sellerID`),
  KEY `studentID` (`studentID`),
  CONSTRAINT `SELLER_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `USER` (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=3;

INSERT INTO `SELLER` (`sellerID`,`studentID`,`accountStatus`) VALUES
(1,'S123456789','Active'),
(2,'245427835','Active');

-- -------------------------------------------------------
-- POSTS
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `POSTS` (
  `itemID`      char(12)       NOT NULL,
  `sellerID`    int(11)        NOT NULL,
  `categoryID`  int(11)        NOT NULL,
  `title`       varchar(150)   NOT NULL,
  `description` text           DEFAULT NULL,
  `price`       decimal(10,2)  DEFAULT 0.00,
  `datePosted`  date           DEFAULT NULL,
  `condition`   varchar(50)    DEFAULT NULL,
  PRIMARY KEY (`itemID`),
  KEY `sellerID` (`sellerID`),
  KEY `categoryID` (`categoryID`),
  CONSTRAINT `POSTS_ibfk_1` FOREIGN KEY (`sellerID`) REFERENCES `SELLER` (`sellerID`),
  CONSTRAINT `POSTS_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `CATEGORY` (`categoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `POSTS` (`itemID`,`sellerID`,`categoryID`,`title`,`description`,`price`,`datePosted`,`condition`) VALUES
('I00000000001',1,1,'Casio Scientific Calculator','fx-991ES PLUS, barely used. Perfect for engineering and math subjects.',550.00,'2026-05-01','Good'),
('I00000000002',1,2,'Java Programming Book','Core Java Vol. 1 by Cay Horstmann, 10th edition. Minor highlighting.',500.00,'2026-05-02','Used'),
('I00000000003',2,3,'Lab Coat (Medium)','White lab coat, worn twice only. Clean and well-kept.',320.00,'2026-05-03','Good'),
('I00000000004',2,5,'T-Square 18 inch','Stainless steel T-square, great condition. No bends or scratches.',600.00,'2026-05-04','Good'),
('I00000000005',1,6,'EasySoft Leather Shoes (Size 42)','Black leather shoes for school. Worn a few times, still in great shape.',750.00,'2026-05-05','Good'),
('I00000000006',2,6,'Aprica Backpack','Spacious laptop backpack with multiple compartments. Lightly used.',980.00,'2026-05-06','Good'),
('I00000000007',1,2,'Data Structures & Algorithms','Textbook by Robert Lafore. Very useful for CS subjects. Minor wear.',450.00,'2026-05-07','Used'),
('I00000000008',2,4,'Dissection Kit (Complete)','Full dissection kit with 10 instruments. Used once in Bio lab.',380.00,'2026-05-08','Good'),
('I00000000009',1,1,'USB-C 65W Charger','Compatible with most laptops and phones. Original packaging.',350.00,'2026-05-09','New'),
('I00000000010',2,7,'Graphing Paper Pad (A3)','50 sheets, unused. Ideal for engineering drawing subjects.',120.00,'2026-05-10','New');

-- -------------------------------------------------------
-- BUYER
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `BUYER` (
  `buyerID`       int(11)     NOT NULL AUTO_INCREMENT,
  `studentID`     char(10)    NOT NULL,
  `paymentMethod` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`buyerID`),
  KEY `studentID` (`studentID`),
  CONSTRAINT `BUYER_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `USER` (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2;

INSERT INTO `BUYER` (`buyerID`,`studentID`,`paymentMethod`) VALUES
(1,'S123456789','Cash');

-- -------------------------------------------------------
-- TRANSACTION
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `TRANSACTION` (
  `transactionID`   char(12)      NOT NULL,
  `itemID`          char(12)      NOT NULL,
  `buyerID`         int(11)       NOT NULL,
  `sellerID`        int(11)       NOT NULL,
  `meetupLocation`  varchar(150)  DEFAULT NULL,
  `transactionDate` datetime      DEFAULT NULL,
  `finalPrice`      decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`transactionID`),
  KEY `itemID` (`itemID`),
  KEY `buyerID` (`buyerID`),
  KEY `sellerID` (`sellerID`),
  CONSTRAINT `TRANSACTION_ibfk_1` FOREIGN KEY (`itemID`)   REFERENCES `POSTS` (`itemID`),
  CONSTRAINT `TRANSACTION_ibfk_2` FOREIGN KEY (`buyerID`)  REFERENCES `BUYER` (`buyerID`),
  CONSTRAINT `TRANSACTION_ibfk_3` FOREIGN KEY (`sellerID`) REFERENCES `SELLER` (`sellerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
