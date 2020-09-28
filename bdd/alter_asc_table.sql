-- MySQL Workbench Synchronization
-- Generated: 2020-09-24 16:41
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Ousmane

SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0;
SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `target_db`.`patients`
    ADD COLUMN `asc_id` INT(11) NULL AFTER `residence_id`,
    ADD INDEX `fk_patients_asc1_idx` (`asc_id` ASC);

CREATE TABLE IF NOT EXISTS `target_db`.`asc`
(
    `id`           INT(11)     NOT NULL AUTO_INCREMENT,
    `nom`          VARCHAR(45) NULL DEFAULT NULL,
    `prenom`       VARCHAR(45) NULL DEFAULT NULL,
    `telephone`          VARCHAR(45) NULL DEFAULT NULL,
    `code_asc`     VARCHAR(45) NULL DEFAULT NULL,
    `profession`   VARCHAR(45) NULL DEFAULT NULL,
    `residence_id` INT(11)     NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_asc_residence1_idx` (`residence_id` ASC),
    CONSTRAINT `fk_asc_residence1`
        FOREIGN KEY (`residence_id`)
            REFERENCES `target_db`.`residence` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8;

ALTER TABLE `target_db`.`patients`
    ADD CONSTRAINT `fk_patients_asc1`
        FOREIGN KEY (`asc_id`)
            REFERENCES `target_db`.`asc` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION;


SET SQL_MODE = @OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
