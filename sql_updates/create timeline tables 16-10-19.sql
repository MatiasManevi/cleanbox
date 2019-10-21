CREATE TABLE `cleanbox`.`properties_timeline` ( `id` INT NOT NULL AUTO_INCREMENT , `property_id` INT NOT NULL , `created_at` VARCHAR(255) NOT NULL , `updated_at` VARCHAR(255) NOT NULL , `deleted_at` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `cleanbox`.`timeline_events` ( `id` INT NOT NULL AUTO_INCREMENT , `timeline_id` INT NOT NULL , `name` VARCHAR(255) NOT NULL , `description` TEXT NOT NULL , `created_at` VARCHAR(255) NOT NULL , `updated_at` VARCHAR(255) NOT NULL , `deleted_at` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `cleanbox`.`event_pictures` 
( `id` INT NOT NULL AUTO_INCREMENT , `event_id` INT NOT NULL ,
 `url` VARCHAR(255) NOT NULL , `created_at` VARCHAR(255) NOT NULL ,
  `updated_at` VARCHAR(255) NOT NULL , `deleted_at` VARCHAR(255) NOT NULL ,
   PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `timeline_events` 
CHANGE `created_at` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CHANGE `updated_at` `updated_at` TIMESTAMP NULL, 
CHANGE `deleted_at` `deleted_at` TIMESTAMP NULL;

ALTER TABLE `event_pictures` 
CHANGE `created_at` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CHANGE `updated_at` `updated_at` TIMESTAMP NULL, 
CHANGE `deleted_at` `deleted_at` TIMESTAMP NULL;

ALTER TABLE `properties_timeline` 
CHANGE `created_at` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CHANGE `updated_at` `updated_at` TIMESTAMP NULL, 
CHANGE `deleted_at` `deleted_at` TIMESTAMP NULL;

ALTER TABLE `propiedades` ADD `timeline_id` INT NOT NULL AFTER `cc_id`;
ALTER TABLE `propiedades` CHANGE `timeline_id` `timeline_id` INT(11) NULL DEFAULT NULL;

ALTER TABLE `mantenimientos` CHANGE `mant_id` `mant_id` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `mant_domicilio` `mant_domicilio` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `mant_prop` `mant_prop` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `mant_inq` `mant_inq` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `mant_prov_1` `mant_prov_1` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `mant_prov_2` `mant_prov_2` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `mant_prov_3` `mant_prov_3` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `mant_prioridad` `mant_prioridad` INT(11) NULL DEFAULT NULL, CHANGE `mant_date_deadline` `mant_date_deadline` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `mant_date_end` `mant_date_end` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `mant_status` `mant_status` INT(11) NULL DEFAULT NULL, CHANGE `mant_calif` `mant_calif` DECIMAL(10,2) NULL DEFAULT NULL, CHANGE `mant_desc` `mant_desc` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `mant_monto` `mant_monto` DECIMAL(10,2) NULL DEFAULT NULL, CHANGE `mant_why_prov` `mant_why_prov` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;