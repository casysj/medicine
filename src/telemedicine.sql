-- Adminer 5.3.0 MySQL 8.0.43 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `telemedicine`;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE `appointments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patientName` varchar(255) NOT NULL,
  `patientEmail` varchar(255) NOT NULL,
  `dateTime` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'confirmed',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `doctor_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6A41727A87F4FB17` (`doctor_id`),
  KEY `idx_doctor_date` (`doctor_id`,`dateTime`),
  KEY `idx_patient_email` (`patientEmail`),
  KEY `idx_status` (`status`),
  CONSTRAINT `FK_6A41727A87F4FB17` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `appointments` (`id`, `patientName`, `patientEmail`, `dateTime`, `status`, `createdAt`, `updatedAt`, `doctor_id`) VALUES
(1,	'Anna Mueller',	'anna.mueller@email.com',	'2025-08-05 11:00:00',	'confirmed',	'2025-08-04 18:18:41',	NULL,	1),
(2,	'Hans Schmidt',	'hans.schmidt@email.com',	'2025-08-05 15:00:00',	'confirmed',	'2025-08-04 18:18:41',	NULL,	1),
(3,	'Maria Weber',	'maria.weber@email.com',	'2025-08-05 09:00:00',	'confirmed',	'2025-08-04 18:18:41',	NULL,	2),
(4,	'Klaus Fischer',	'klaus.fischer@email.com',	'2025-08-04 10:00:00',	'completed',	'2025-08-04 18:18:41',	NULL,	1),
(5,	'Petra Wagner',	'petra.wagner@email.com',	'2025-08-04 14:00:00',	'completed',	'2025-08-04 18:18:41',	NULL,	2),
(6,	'Thomas Bauer',	'thomas.bauer@email.com',	'2025-08-04 16:00:00',	'completed',	'2025-08-04 18:18:41',	NULL,	3),
(7,	'Sabine Richter',	'sabine.richter@email.com',	'2025-08-03 11:30:00',	'cancelled',	'2025-08-04 18:18:41',	NULL,	1),
(8,	'Michael Hoffmann',	'michael.hoffmann@email.com',	'2025-08-03 15:30:00',	'cancelled',	'2025-08-04 18:18:41',	NULL,	2),
(9,	'Julia Neumann',	'julia.neumann@email.com',	'2025-08-07 09:00:00',	'confirmed',	'2025-08-04 18:18:41',	NULL,	3),
(10,	'Robert Klein',	'robert.klein@email.com',	'2025-08-07 13:30:00',	'confirmed',	'2025-08-04 18:18:41',	NULL,	2),
(11,	'hi',	'a@b.c',	'2025-08-05 10:30:00',	'cancelled',	'2025-08-05 23:52:37',	'2025-08-07 00:34:29',	3);

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE `doctors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `specialization_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B67687BEFA846217` (`specialization_id`),
  CONSTRAINT `FK_B67687BEFA846217` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `doctors` (`id`, `name`, `createdAt`, `updatedAt`, `specialization_id`) VALUES
(1,	'Doctor_A',	'2025-08-04 17:22:38',	NULL,	1),
(2,	'Doctor_B',	'2025-08-04 17:22:46',	NULL,	2),
(3,	'Doctor_C',	'2025-08-04 17:22:53',	NULL,	1),
(4,	'Doctor_D',	'2025-08-04 17:23:01',	NULL,	3);

DROP TABLE IF EXISTS `specializations`;
CREATE TABLE `specializations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `specializations` (`id`, `name`, `createdAt`, `updatedAt`) VALUES
(1,	'Special_A',	'2025-08-04 17:21:52',	NULL),
(2,	'Special_B',	'2025-08-04 17:22:00',	NULL),
(3,	'Special_C',	'2025-08-04 17:22:08',	NULL);

DROP TABLE IF EXISTS `time_slots`;
CREATE TABLE `time_slots` (
  `id` int NOT NULL AUTO_INCREMENT,
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  `isAvailable` tinyint(1) NOT NULL DEFAULT '1',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `doctor_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8D06D4AC87F4FB17` (`doctor_id`),
  KEY `idx_doctor_available` (`doctor_id`,`isAvailable`),
  KEY `idx_start_time` (`startTime`),
  CONSTRAINT `FK_8D06D4AC87F4FB17` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `time_slots` (`id`, `startTime`, `endTime`, `isAvailable`, `createdAt`, `updatedAt`, `doctor_id`) VALUES
(1,	'2025-08-05 09:00:00',	'2025-08-05 09:30:00',	1,	'2025-08-04 18:18:25',	NULL,	1),
(2,	'2025-08-05 10:00:00',	'2025-08-05 10:30:00',	1,	'2025-08-04 18:18:25',	NULL,	1),
(3,	'2025-08-05 14:00:00',	'2025-08-05 14:30:00',	1,	'2025-08-04 18:18:25',	NULL,	1),
(4,	'2025-08-06 09:30:00',	'2025-08-06 10:00:00',	1,	'2025-08-04 18:18:25',	NULL,	1),
(5,	'2025-08-05 11:00:00',	'2025-08-05 11:30:00',	0,	'2025-08-04 18:18:25',	NULL,	1),
(6,	'2025-08-05 15:00:00',	'2025-08-05 15:30:00',	0,	'2025-08-04 18:18:25',	NULL,	1),
(7,	'2025-08-05 08:00:00',	'2025-08-05 08:30:00',	1,	'2025-08-04 18:18:25',	NULL,	2),
(8,	'2025-08-05 13:00:00',	'2025-08-05 13:30:00',	1,	'2025-08-04 18:18:25',	NULL,	2),
(9,	'2025-08-06 10:00:00',	'2025-08-06 10:30:00',	1,	'2025-08-04 18:18:25',	NULL,	2),
(10,	'2025-08-06 16:00:00',	'2025-08-06 16:30:00',	1,	'2025-08-04 18:18:25',	NULL,	2),
(11,	'2025-08-05 09:00:00',	'2025-08-05 09:30:00',	0,	'2025-08-04 18:18:25',	NULL,	2),
(12,	'2025-08-05 10:30:00',	'2025-08-05 11:00:00',	1,	'2025-08-04 18:18:25',	'2025-08-07 00:34:29',	3),
(13,	'2025-08-05 15:30:00',	'2025-08-05 16:00:00',	1,	'2025-08-04 18:18:25',	NULL,	3),
(14,	'2025-08-06 08:30:00',	'2025-08-06 09:00:00',	1,	'2025-08-04 18:18:25',	NULL,	3);

-- 2025-08-06 22:52:13 UTC
