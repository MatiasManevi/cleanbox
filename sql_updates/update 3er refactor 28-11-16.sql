
CREATE TABLE IF NOT EXISTS `settings` ( `id` INT NOT NULL AUTO_INCREMENT , `iibb_bank_tax` DECIMAL(10,2) NOT NULL , `iva_tax` DECIMAL(10,2) NOT NULL , `begin_cash_zero` TINYINT NOT NULL ,`automatic_receive` TINYINT NOT NULL, `code_control` TINYINT NOT NULL , `logo` VARCHAR(255) NOT NULL , `cuit` VARCHAR(255) NOT NULL , `iibb_number` VARCHAR(255) NOT NULL , `user_id` INT NOT NULL , `init_activity_date` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL , `address` VARCHAR(255) NOT NULL , `activity` VARCHAR(556) NOT NULL , `cel_phone` VARCHAR(255) NOT NULL , `phone` VARCHAR(255) NOT NULL , `site_url` VARCHAR(255) NOT NULL , `fiscal_status` VARCHAR(255) NOT NULL , `name` VARCHAR(556) NOT NULL , `zip_code` VARCHAR(255) NOT NULL , `city` VARCHAR(255) NOT NULL , `state` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`) ) ENGINE = InnoDB;
ALTER TABLE `settings` CHANGE `iibb_bank_tax` `iibb_bank_tax` VARCHAR(255) NOT NULL, CHANGE `iva_tax` `iva_tax` VARCHAR(255) NOT NULL;

INSERT INTO `settings` (`id`, `iibb_bank_tax`, `iva_tax`, `automatic_receive`, `begin_cash_zero`, `code_control`, `logo`, `cuit`, `iibb_number`, `user_id`, `init_activity_date`, `email`, `address`, `activity`, `cel_phone`, `phone`, `site_url`, `fiscal_status`, `name`, `zip_code`, `city`, `state`) VALUES (NULL, '0.0245', '0.21', '0', '0', '0', '', '', '', '1', '', '', '', '', '', '', '', '', '', '', '', '');



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

ALTER TABLE `contratos` 
ADD `honorary_cuotes_payed` INT NOT NULL DEFAULT '0' ,
ADD `honorary_cuotes` INT NOT NULL DEFAULT '1' ,
ADD `cc_id` INT NOT NULL , 
ADD `client_id` INT NOT NULL , 
ADD `prop_id` INT NOT NULL , 
ADD `gar1_id` INT NOT NULL , 
ADD `gar2_id` INT NOT NULL , 
ADD `con_date_created` VARCHAR(255) NOT NULL , 
ADD `con_date_renovated` VARCHAR(255) NOT NULL , 
ADD `con_date_declined` VARCHAR(255) NOT NULL ;
ALTER TABLE `contratos` 
ADD `warranty_cuotes` INT NOT NULL DEFAULT '1' , 
ADD `warranty_cuotes_payed` INT NOT NULL DEFAULT '0' ;


/* conceptos
iva_percibe = si el concepto debe percibir iva 21%
gestion_percibe = si el concepto debe calcular una gestion de cobro
interes_percibe = si el concepto debe calcular intereses */

ALTER TABLE `conceptos` ADD `iva_percibe` TINYINT NOT NULL DEFAULT '0' , ADD `gestion_percibe` TINYINT NOT NULL DEFAULT '0' , ADD `interes_percibe` TINYINT NOT NULL DEFAULT '0' ;
ALTER TABLE `conceptos` ADD `force_account` VARCHAR(255) NOT NULL ;

/* providers_rols = para generar dinamicamente areas de proveedores
id
rol */




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

ALTER TABLE `providers_rols`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `providers_rols`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;


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


CREATE TABLE IF NOT EXISTS `services_control` (
  `id` int(11) NOT NULL,
  `service` varchar(255) NOT NULL,
  `contract` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `month_checked` varchar(255) NOT NULL,
  `trans` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `services_control`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `services_control`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


/* comentarios
prop_id = id de la propiedad
cc_id = id de la cuenta corriente del propietario */

ALTER TABLE `comentarios` ADD `prop_id` INT NOT NULL , ADD `cc_id` INT NOT NULL ;


ALTER TABLE `periodos` DROP `per_iva`;

DROP TABLE liquidado;
DROP TABLE sections;
DROP TABLE gallery;
/*DROP TABLE transferencias; esto despues*/


/*
crear tablas:
areas_proveedores-
mantenimientos-
proveedores-
proveedores_nota-
*/



CREATE TABLE IF NOT EXISTS `areas_proveedores` (
  `area_id` int(11) NOT NULL,
  `area_prov` int(11) NOT NULL,
  `area_area` varchar(500) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;


--
-- Dumping data for table `areas_proveedores`
--


INSERT INTO `areas_proveedores` (`area_id`, `area_prov`, `area_area`) VALUES
(31, 1, 'Electricista'),
(2, 2, 'Electricista'),
(3, 3, 'Electricista'),
(4, 4, 'Plomero'),
(5, 5, 'Plomero'),
(6, 6, 'Plomero'),
(37, 7, 'Techistas'),
(8, 8, 'Albañil'),
(9, 9, 'Carpintero Pdas'),
(10, 10, 'Carpintero Pdas'),
(11, 11, 'Carpintero Pdas'),
(36, 12, 'Refrigeracion'),
(38, 13, 'Refrigeracion'),
(14, 14, 'Refrigeracion'),
(15, 15, 'Vidriero'),
(16, 16, 'Persianas'),
(17, 17, 'Cerrajeros'),
(18, 18, 'Cerrajeros'),
(19, 19, 'Ascensores'),
(20, 20, 'Pintor Edificios'),
(21, 21, 'Pintor Edificios'),
(22, 22, 'Pintor Edificios'),
(24, 24, 'Electricista'),
(25, 26, 'Cerrajeros'),
(40, 27, 'Agrimensor'),
(27, 28, 'Electricista'),
(28, 29, 'Cerrajeros'),
(29, 30, 'Techistas'),
(42, 31, 'Plomero'),
(34, 33, 'Plomero'),
(35, 34, 'Ascensores'),
(39, 13, 'PLOMERO'),
(41, 27, 'Gasista');


ALTER TABLE `areas_proveedores`
  ADD PRIMARY KEY (`area_id`);


ALTER TABLE `areas_proveedores`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;

CREATE TABLE IF NOT EXISTS `mantenimientos` (
  `mant_id` int(11) NOT NULL,
  `mant_domicilio` varchar(500) NOT NULL,
  `mant_prop` varchar(500) NOT NULL,
  `mant_inq` varchar(500) NOT NULL,
  `mant_prov_1` varchar(500) NOT NULL,
  `mant_prov_2` varchar(500) NOT NULL,
  `mant_prov_3` varchar(500) NOT NULL,
  `mant_prioridad` int(11) NOT NULL,
  `mant_date_deadline` varchar(500) NOT NULL,
  `mant_date_end` varchar(500) NOT NULL,
  `mant_status` int(11) NOT NULL,
  `mant_calif` decimal(10,2) NOT NULL,
  `mant_desc` varchar(500) NOT NULL,
  `mant_monto` decimal(10,2) NOT NULL,
  `mant_why_prov` varchar(500) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;

ALTER TABLE `mantenimientos`
  ADD PRIMARY KEY (`mant_id`);


ALTER TABLE `mantenimientos`
  MODIFY `mant_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;

CREATE TABLE IF NOT EXISTS `proveedores` (
  `prov_id` int(11) NOT NULL,
  `prov_name` varchar(500) NOT NULL,
  `prov_tel` varchar(500) NOT NULL,
  `prov_domicilio` varchar(500) NOT NULL,
  `prov_email` varchar(500) NOT NULL,
  `prov_nota` decimal(10,2) NOT NULL,
  `prov_bussy` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;


ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`prov_id`);
ALTER TABLE `proveedores`
  MODIFY `prov_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;



CREATE TABLE IF NOT EXISTS `proveedores_nota` (
  `nota_id` int(11) NOT NULL,
  `nota_prov_id` int(11) NOT NULL,
  `nota_garantia` int(11) NOT NULL,
  `nota_exp` int(11) NOT NULL,
  `nota_timing` int(11) NOT NULL,
  `nota_presup` int(11) NOT NULL,
  `nota_trust` int(11) NOT NULL,
  `nota_calidad` int(11) NOT NULL,
  `nota_total` decimal(10,2) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


ALTER TABLE `proveedores_nota`
  ADD PRIMARY KEY (`nota_id`);
ALTER TABLE `proveedores_nota`
  MODIFY `nota_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;


/* todos los campos de montos Q FALTEN hacerlos tipo decimal 10,2 */

ALTER TABLE `mantenimientos` CHANGE `mant_calif` `mant_calif` DECIMAL(10,2) NOT NULL;
ALTER TABLE `proveedores` CHANGE `prov_nota` `prov_nota` DECIMAL(10,2) NOT NULL;
ALTER TABLE `proveedores_nota` CHANGE `nota_total` `nota_total` DECIMAL(10,2) NOT NULL;

ALTER TABLE `creditos` CHANGE `cred_monto` `cred_monto` DECIMAL(10,2) NOT NULL;
ALTER TABLE `cuentas_corrientes` CHANGE `cc_saldo` `cc_saldo` DECIMAL(10,2) NOT NULL;
ALTER TABLE `cuentas_corrientes` CHANGE `cc_varios` `cc_varios` DECIMAL(10,2) NOT NULL;
ALTER TABLE `debitos` CHANGE `deb_monto` `deb_monto` DECIMAL(10,2) NOT NULL;
ALTER TABLE `periodos` CHANGE `per_monto` `per_monto` DECIMAL(10,2) NOT NULL;

ALTER TABLE `man_users` ADD `admin_id` INT NOT NULL;

UPDATE `creditos` SET `cred_mes_alq` = 'Diciembre 2015' WHERE `creditos`.`cred_id` = 8951;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10632;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10617;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10656;
UPDATE `creditos` SET `cred_mes_alq` = 'Marzo 2016' WHERE `creditos`.`cred_id` = 13615;
UPDATE `creditos` SET `cred_mes_alq` = 'Marzo 2016' WHERE `creditos`.`cred_id` = 13617;
UPDATE `creditos` SET `cred_mes_alq` = 'Noviembre 2016' WHERE `creditos`.`cred_id` = 16862;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10460;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10567;
UPDATE `creditos` SET `cred_mes_alq` = 'Abril 2016' WHERE `creditos`.`cred_id` = 11894;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13560;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13561;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13368;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13369;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13620;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13619;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13558;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13559;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13562;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13563;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13564;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13565;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13566;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13589;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13593; 
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13594; 
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13595;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13606; 
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13607; 
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13608; 
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13609; 
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13610; 
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13611;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13612; 
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13613;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13621;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2016' WHERE `creditos`.`cred_id` = 13622;
UPDATE `creditos` SET `cred_mes_alq` = 'Agosto 2016' WHERE `creditos`.`cred_id` = 14789;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2014' WHERE `creditos`.`cred_id` = 45;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2014' WHERE `creditos`.`cred_id` = 46;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2014' WHERE `creditos`.`cred_id` = 806;
UPDATE `creditos` SET `cred_mes_alq` = 'Junio 2014' WHERE `creditos`.`cred_id` = 807;
UPDATE `creditos` SET `cred_mes_alq` = 'Octubre 2014' WHERE `creditos`.`cred_id` = 1796;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2015' WHERE `creditos`.`cred_id` = 2427;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2015' WHERE `creditos`.`cred_id` = 2467;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2015' WHERE `creditos`.`cred_id` = 2500;
UPDATE `creditos` SET `cred_mes_alq` = 'Marzo 2015' WHERE `creditos`.`cred_id` = 2651;
UPDATE `creditos` SET `cred_mes_alq` = 'Marzo 2015' WHERE `creditos`.`cred_id` = 2725;
UPDATE `creditos` SET `cred_mes_alq` = 'Marzo 2015' WHERE `creditos`.`cred_id` = 2850; 
UPDATE `creditos` SET `cred_mes_alq` = 'Abril 2015' WHERE `creditos`.`cred_id` = 2851;
UPDATE `creditos` SET `cred_mes_alq` = 'Mayo 2015' WHERE `creditos`.`cred_id` = 4148;
UPDATE `creditos` SET `cred_mes_alq` = 'Mayo 2015' WHERE `creditos`.`cred_id` = 4230;
UPDATE `creditos` SET `cred_mes_alq` = 'Mayo 2015' WHERE `creditos`.`cred_id` = 4229;
UPDATE `creditos` SET `cred_mes_alq` = 'Noviembre 2015' WHERE `creditos`.`cred_id` = 7123;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10560; 
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10561;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10709; 
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10708;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10566;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10705; 
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10704;
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10603; 
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10604;
UPDATE `creditos` SET `cred_mes_alq` = 'Marzo 2016' WHERE `creditos`.`cred_id` = 11013; 
UPDATE `creditos` SET `cred_mes_alq` = 'Marzo 2016' WHERE `creditos`.`cred_id` = 11014;
UPDATE `creditos` SET `cred_mes_alq` = 'Abril 2016' WHERE `creditos`.`cred_id` = 13557;




UPDATE `debitos` SET `deb_mes` = 'Febrero 2016' WHERE `debitos`.`deb_id` = 8429;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10517; 
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10518;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10393;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10674; 
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10675;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10403;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10404;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10405;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10406;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10407;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10516;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10515;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10523;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10524; 
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10525;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10633;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10640; 
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10641;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10657;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10677;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10678;
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10681; 
UPDATE `debitos` SET `deb_mes` = 'Junio 2016' WHERE `debitos`.`deb_id` = 10682;
UPDATE `debitos` SET `deb_mes` = 'Septiembre 2016' WHERE `debitos`.`deb_id` = 12201; 
UPDATE `debitos` SET `deb_mes` = 'Septiembre 2016' WHERE `debitos`.`deb_id` = 12202;
UPDATE `debitos` SET `deb_mes` = 'Octubre 2016' WHERE `debitos`.`deb_id` = 12632;
UPDATE `debitos` SET `deb_mes` = 'Noviembre 2016' WHERE `debitos`.`deb_id` = 13169; 
UPDATE `debitos` SET `deb_mes` = 'Noviembre 2016' WHERE `debitos`.`deb_id` = 13170;
UPDATE `debitos` SET `deb_mes` = 'Febrero 2015' WHERE `debitos`.`deb_id` = 2066; 
UPDATE `debitos` SET `deb_mes` = 'Febrero 2015' WHERE `debitos`.`deb_id` = 2067;
UPDATE `debitos` SET `deb_mes` = 'Marzo 2015' WHERE `debitos`.`deb_id` = 2288; 
UPDATE `debitos` SET `deb_mes` = 'Marzo 2015' WHERE `debitos`.`deb_id` = 2289;
UPDATE `debitos` SET `deb_mes` = 'Marzo 2015' WHERE `debitos`.`deb_id` = 2379; 
UPDATE `debitos` SET `deb_mes` = 'Marzo 2015' WHERE `debitos`.`deb_id` = 2380;
UPDATE `debitos` SET `deb_mes` = 'Mayo 2015' WHERE `debitos`.`deb_id` = 3485; 
UPDATE `debitos` SET `deb_mes` = 'Mayo 2015' WHERE `debitos`.`deb_id` = 3486;
UPDATE `debitos` SET `deb_mes` = 'Junio 2015' WHERE `debitos`.`deb_id` = 4189;
UPDATE `debitos` SET `deb_mes` = 'Junio 2015' WHERE `debitos`.`deb_id` = 4190;
UPDATE `debitos` SET `deb_mes` = 'Septiembre 2015' WHERE `debitos`.`deb_id` = 5851;
UPDATE `debitos` SET `deb_mes` = 'Septiembre 2015' WHERE `debitos`.`deb_id` = 5852;



UPDATE `conceptos` SET `conc_desc` = 'Deposito de garantia' WHERE `conceptos`.`conc_id` = 148;

UPDATE `conceptos` SET `iva_percibe` = '1', `gestion_percibe` = '1', `interes_percibe` = '1' WHERE `conceptos`.`conc_id` = 2; 
UPDATE `conceptos` SET `gestion_percibe` = '1' WHERE `conceptos`.`conc_id` = 22; 
UPDATE `conceptos` SET `conc_desc` = 'Honorarios', `iva_percibe` = '1' WHERE `conceptos`.`conc_id` = 24; UPDATE `conceptos` SET `gestion_percibe` = '1' WHERE `conceptos`.`conc_id` = 26; 
UPDATE `conceptos` SET `iva_percibe` = '1', `gestion_percibe` = '1', `interes_percibe` = '1' WHERE `conceptos`.`conc_id` = 28;

UPDATE `creditos` SET `cred_concepto`= 'Honorarios'  WHERE `cred_concepto` LIKE 'Comision';
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10707; 
UPDATE `creditos` SET `cred_mes_alq` = 'Febrero 2016' WHERE `creditos`.`cred_id` = 10706;

INSERT INTO `conceptos` (`conc_id`, `conc_desc`, `conc_tipo`, `conc_cc`, `conc_control`, `iva_percibe`, `gestion_percibe`, `interes_percibe`, `force_account`) VALUES (NULL, 'Reserva', 'Entrada', 'cc_varios', '0', '0', '0', '0', '');

UPDATE `contratos` SET `con_tipo` = 'Alquiler Comercial' WHERE `contratos`.`con_id` = 103; 
UPDATE `contratos` SET `con_tipo` = 'Alquiler Comercial' WHERE `contratos`.`con_id` = 248;

DELETE FROM `conceptos` WHERE `conc_desc` = 'Alquiler Comercial N';

UPDATE `creditos` SET `cred_concepto` = 'Deposito de garantia' WHERE `creditos`.`cred_id` = 4953; 
UPDATE `creditos` SET `cred_concepto` = 'Deposito de garantia' WHERE `creditos`.`cred_id` = 7703; 
UPDATE `creditos` SET `cred_concepto` = 'Deposito de garantia' WHERE `creditos`.`cred_id` = 7922; 
UPDATE `creditos` SET `cred_concepto` = 'Deposito de garantia' WHERE `creditos`.`cred_id` = 8344; 
UPDATE `creditos` SET `cred_concepto` = 'Deposito de garantia' WHERE `creditos`.`cred_id` = 14958;

UPDATE `man_users` SET `admin_id` = '10' WHERE `man_users`.`id` = 10; 
UPDATE `man_users` SET `admin_id` = '10' WHERE `man_users`.`id` = 11;
UPDATE `settings` SET `user_id` = '10' WHERE `settings`.`id` = 1;











































