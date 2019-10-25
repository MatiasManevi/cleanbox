-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 21-10-2019 a las 16:04:35
-- Versión del servidor: 10.2.3-MariaDB-log
-- Versión de PHP: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cleanbox`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspections`
--

CREATE TABLE `inspections` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `renter_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `momentum` int(11) NOT NULL COMMENT '1: antes de alquiler, 2: durante alquiler, 3: despues de alquiler',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `inspections`
--
ALTER TABLE `inspections`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE `cleanbox`.`inspection_pictures` ( 
 `id` INT NOT NULL AUTO_INCREMENT, 
 `inspection_id` INT NOT NULL ,
 `url` VARCHAR(255) NOT NULL ,
 `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `updated_at` TIMESTAMP NULL DEFAULT NULL ,
 `deleted_at` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `cleanbox`.`property_pictures` ( 
  `id` INT NOT NULL AUTO_INCREMENT, 
  `property_id` INT NOT NULL ,
  `url` VARCHAR(255) NOT NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `updated_at` TIMESTAMP NULL DEFAULT NULL , 
  `deleted_at` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `cleanbox`.`manteinment_pictures` ( 
  `id` INT NOT NULL AUTO_INCREMENT , 
  `manteinment_id` INT NOT NULL , 
  `url` VARCHAR(255) NOT NULL , 
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
  `updated_at` TIMESTAMP NOT NULL , 
  `deleted_at` TIMESTAMP NOT NULL , 
  PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `inspections` 
ADD `renter` VARCHAR(255) NOT NULL AFTER `renter_id`, 
ADD `address` VARCHAR(255) NOT NULL AFTER `renter`;
ALTER TABLE `inspections` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `inspections` CHANGE `date` `date` VARCHAR(255) NULL DEFAULT NULL;