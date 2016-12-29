
CREATE TABLE `cleanbox`.`settings` ( `id` INT NOT NULL AUTO_INCREMENT , `iibb_bank_tax` DECIMAL(10,2) NOT NULL , `iva_tax` DECIMAL(10,2) NOT NULL , `begin_cash_zero` TINYINT NOT NULL , `code_control` TINYINT NOT NULL , `logo` VARCHAR(255) NOT NULL , `cuit` VARCHAR(255) NOT NULL , `iibb_number` VARCHAR(255) NOT NULL , `user_id` INT NOT NULL , `init_activity_date` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL , `address` VARCHAR(255) NOT NULL , `activity` VARCHAR(556) NOT NULL , `cel_phone` VARCHAR(255) NOT NULL , `phone` VARCHAR(255) NOT NULL , `site_url` VARCHAR(255) NOT NULL , `fiscal_status` VARCHAR(255) NOT NULL , `name` VARCHAR(556) NOT NULL , `zip_code` VARCHAR(255) NOT NULL , `city` VARCHAR(255) NOT NULL , `state` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`) ) ENGINE = InnoDB;
ALTER TABLE `settings` CHANGE `iibb_bank_tax` `iibb_bank_tax` VARCHAR(255) NOT NULL, CHANGE `iva_tax` `iva_tax` VARCHAR(255) NOT NULL;

INSERT INTO `cleanbox`.`settings` (`id`, `iibb_bank_tax`, `iva_tax`, `begin_cash_zero`, `code_control`, `logo`, `cuit`, `iibb_number`, `user_id`, `init_activity_date`, `email`, `address`, `activity`, `cel_phone`, `phone`, `site_url`, `fiscal_status`, `name`, `zip_code`, `city`, `state`) VALUES (NULL, '0.0245', '0.21', '0', '0', '', '20-07583942-2', '20-07583942-2', '1', '20-05-74', 'info@andresdavinia.com.ar', 'SANTIAGO DEL ESTERO 1477', 'Compras-Ventas-Administracion de Propiedades-Alquileres-Tasaciones-Remates-Loteos', '154334168', '0376-4425983', 'www.andresdavinia.com.ar', 'I.V.A RESPONSABLE INSCRIPTO', 'Andres Davinia Inmobiliaria', '3300', 'Posadas', 'Misiones');



/* contratos
 warranty_cuotes =  cantidad de cuotas en que se cobrara deposito de garantia
 warranty_cuotes_payed = cantidad de uotas que ya se cobraron
 comision_cuotes =  cantidad de cuotas en que se cobrara honorarios
 comision_cuotes_payed = cantidad de cuotas que ya se cobraron
 cc_id = propietario
 client_id = inquilino
 prop_id = propiedad
 gar1_id = garante 1
 gar2_id = garante 2
 con_date_created	varchar(255)
 con_date_renovated	varchar(255)
 con_date_declined	varchar(255) */

ALTER TABLE `contratos` ADD `honorary_cuotes` INT NOT NULL DEFAULT '1' , ADD `honorary_cuotes_payed` INT NOT NULL DEFAULT '0' , ADD `cc_id` INT NOT NULL , ADD `client_id` INT NOT NULL , ADD `prop_id` INT NOT NULL , ADD `gar1_id` INT NOT NULL , ADD `gar2_id` INT NOT NULL , ADD `con_date_created` VARCHAR(255) NOT NULL , ADD `con_date_renovated` VARCHAR(255) NOT NULL , ADD `con_date_declined` VARCHAR(255) NOT NULL ;
ALTER TABLE `contratos` ADD `warranty_cuotes` INT NOT NULL DEFAULT '1' , ADD `warranty_cuotes_payed` INT NOT NULL DEFAULT '0' ;


/* conceptos
iva_percibe = si el concepto debe percibir iva 21%
gestion_percibe = si el concepto debe calcular una gestion de cobro
interes_percibe = si el concepto debe calcular intereses */

ALTER TABLE `conceptos` ADD `iva_percibe` TINYINT NOT NULL DEFAULT '0' , ADD `gestion_percibe` TINYINT NOT NULL DEFAULT '0' , ADD `interes_percibe` TINYINT NOT NULL DEFAULT '0' ;
ALTER TABLE `conceptos` ADD `force_account` VARCHAR(255) NOT NULL ;

/* providers_rols = para generar dinamicamente areas de proveedores
id
rol */


-- phpMyAdmin SQL Dump
-- version 4.3.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2016 at 04:32 PM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cleanbox`
--

-- --------------------------------------------------------

--
-- Table structure for table `providers_rols`
--

CREATE TABLE IF NOT EXISTS `providers_rols` (
  `id` int(11) NOT NULL,
  `rol` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `providers_rols`
--

INSERT INTO `providers_rols` (`id`, `rol`) VALUES
(18, 'Plomero'),
(19, 'Carpintero'),
(20, 'Refrigeracion'),
(21, 'Persianas'),
(22, 'Vidriero'),
(23, 'Ascensores'),
(24, 'Pintor'),
(25, 'Escribano'),
(26, 'Abogado'),
(27, 'Agrimensor'),
(28, 'Contador'),
(29, 'Techistas'),
(30, 'Cerrajeros'),
(31, 'Electricista'),
(32, 'Gasista'),
(33, 'Albañil'),
(34, 'Aire Acond.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `providers_rols`
--
ALTER TABLE `providers_rols`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `providers_rols`
--
ALTER TABLE `providers_rols`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



/* creditos
receive_number = campo para permitir guardar el numero del recibo de papel que se va a usar
con_id = contrato
cc_id
client_id
is_transfer default 0
borrar cred_interes_calculados */

ALTER TABLE `creditos` DROP `cred_interes_calculados`;
ALTER TABLE `creditos` ADD `con_id` INT NOT NULL AFTER `cred_id`, ADD `cc_id` INT NOT NULL AFTER `con_id`, ADD `client_id` INT NOT NULL AFTER `cc_id`, ADD `is_transfer` TINYINT NOT NULL DEFAULT '0' AFTER `client_id`, ADD `receive_number` VARCHAR(255) NOT NULL AFTER `is_transfer`;

/* intereses_mora
con_id
cc_id
client_id
int_amount */

ALTER TABLE `intereses_mora` ADD `con_id` INT NOT NULL , ADD `cc_id` INT NOT NULL , ADD `client_id` INT NOT NULL , ADD `int_amount` DECIMAL(10,2) NOT NULL ;


/* mensuales 
borrar men_date, men_info */

ALTER TABLE `mensuales` DROP `men_date`;
ALTER TABLE `mensuales` DROP `men_info`;


/* propiedades
cc_id
prop_date_created	varchar(255) */

ALTER TABLE `propiedades` ADD `cc_id` INT NOT NULL , ADD `prop_date_created` VARCHAR(255) NOT NULL ;


/* debitos
cc_id
is_transfer default 0 */

ALTER TABLE `debitos` ADD `cc_id` INT NOT NULL AFTER `deb_id`, ADD `is_transfer` TINYINT NOT NULL DEFAULT '0' AFTER `cc_id`;


/* cuentas_corrientes
client_id = id de tabla clientes del propietario
cc_date_created	varchar(255) */

ALTER TABLE `cuentas_corrientes` ADD `client_id` INT NOT NULL , ADD `cc_date_created` VARCHAR(255) NOT NULL ;


/* services_control = para gestioanr el control de boletas de servicios que no se pagan por la inmo sino que lo hacen los
inquilinos y estos presentan la boleta pagada
id
service
contract
date
month_checked */

CREATE TABLE `cleanbox`.`services_control` ( `id` INT NOT NULL AUTO_INCREMENT , `service` VARCHAR(255) NOT NULL , `contract` INT NOT NULL , `date` VARCHAR(255) NOT NULL , `month_checked` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`) ) ENGINE = InnoDB;
ALTER TABLE `services_control` ADD `trans` INT NOT NULL ;


/* comentarios
prop_id = id de la propiedad
cc_id = id de la cuenta corriente del propietario */

ALTER TABLE `comentarios` ADD `prop_id` INT NOT NULL , ADD `cc_id` INT NOT NULL ;


/* todos los campos de montos Q FALTEN hacerlos tipo decimal 10,2 */

ALTER TABLE `mantenimientos` CHANGE `mant_calif` `mant_calif` DECIMAL(10,2) NOT NULL;
ALTER TABLE `proveedores` CHANGE `prov_nota` `prov_nota` DECIMAL(10,2) NOT NULL;
ALTER TABLE `proveedores_nota` CHANGE `nota_total` `nota_total` DECIMAL(10,2) NOT NULL;
ALTER TABLE `man_users` ADD `admin_id` INT NOT NULL ;

DROP TABLE liquidado;
DROP TABLE sections;
DROP TABLE gallery;
DROP TABLE transferencias;
ALTER TABLE `periodos` DROP `per_iva`;

/* updates davinia */
UPDATE `creditos` SET `cred_concepto`='Servicios' WHERE `cred_concepto` = 'servicio'