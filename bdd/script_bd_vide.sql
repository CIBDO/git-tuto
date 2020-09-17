-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema target_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema target_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `target_db` DEFAULT CHARACTER SET utf8 ;
USE `target_db` ;

-- -----------------------------------------------------
-- Table `target_db`.`services`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`services` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`residence`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`residence` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(100) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`patients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`patients` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_technique` VARCHAR(45) NULL,
  `nom` VARCHAR(45) NULL,
  `prenom` VARCHAR(45) NULL,
  `prenom2` VARCHAR(45) NULL,
  `nom_conjoint` VARCHAR(45) NULL,
  `contact_conjoint` VARCHAR(45) NULL,
  `nom_pere` VARCHAR(45) NULL,
  `nom_mere` VARCHAR(45) NULL,
  `contact_pere` VARCHAR(45) NULL,
  `contact_mere` VARCHAR(45) NULL,
  `personne_a_prev` VARCHAR(45) NULL,
  `nom_jeune_fille` VARCHAR(45) NULL,
  `date_naissance` DATE NULL,
  `sexe` ENUM('m', 'f') NULL,
  `adresse` VARCHAR(45) NULL,
  `ethnie` VARCHAR(45) NULL,
  `profession` VARCHAR(45) NULL,
  `telephone` VARCHAR(45) NULL,
  `telephone2` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  `autre_infos` TEXT NULL,
  `date_creation` DATETIME NULL,
  `residence_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_patients_residence1_idx` (`residence_id` ASC),
  CONSTRAINT `fk_patients_residence1`
    FOREIGN KEY (`residence_id`)
    REFERENCES `target_db`.`residence` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NULL,
  `nom` VARCHAR(45) NULL,
  `prenom` VARCHAR(45) NULL,
  `telephone` VARCHAR(45) NULL,
  `login` VARCHAR(45) NULL,
  `password` VARCHAR(100) NULL,
  `profile` ENUM('admin', 'agent', 'medecin', 'laboratin', 'pharmacien', 'comptable') NULL,
  `permissions` VARCHAR(255) NULL,
  `status` ENUM('actif', 'inactif') NULL,
  `services_id` INT NOT NULL,
  `prestataire` TINYINT NULL,
  `forms_assoc` VARCHAR(100) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_services1_idx` (`services_id` ASC),
  CONSTRAINT `fk_user_services1`
    FOREIGN KEY (`services_id`)
    REFERENCES `target_db`.`services` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`dossiers_consultations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`dossiers_consultations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NULL,
  `patients_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `motif` TEXT NULL,
  `date_creation` DATETIME NULL,
  `debut_maladie` DATE NULL,
  `histoire` TEXT NULL,
  `examen_clinique` TEXT NULL,
  `commentaire` TEXT NULL,
  `confidentialite` VARCHAR(255) NULL,
  `resume` TEXT NULL,
  `resultat_exam_comp` TEXT NULL,
  `etat` ENUM('ouvert', 'clos') NULL,
  `form_type_id` INT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_dossiers_patient_patients_idx` (`patients_id` ASC),
  INDEX `fk_dossiers_cosultations_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_dossiers_patient_patients`
    FOREIGN KEY (`patients_id`)
    REFERENCES `target_db`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_dossiers_cosultations_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`actes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`actes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(100) NULL,
  `code` VARCHAR(45) NULL,
  `services_id` INT NOT NULL,
  `prix` FLOAT NULL,
  `type` ENUM('consultation', 'labo', 'imagerie', 'autre') NULL DEFAULT 'autre',
  `unite` ENUM('URENI', 'Dispensaire', 'Maternité', 'Autre') NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_actes_services1_idx` (`services_id` ASC),
  CONSTRAINT `fk_actes_services1`
    FOREIGN KEY (`services_id`)
    REFERENCES `target_db`.`services` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`prix_actes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`prix_actes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `prix` VARCHAR(45) NULL,
  `actes_id` INT NOT NULL,
  `libelle` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_prix_actes_actes1_idx` (`actes_id` ASC),
  CONSTRAINT `fk_prix_actes_actes1`
    FOREIGN KEY (`actes_id`)
    REFERENCES `target_db`.`actes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`consultations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`consultations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dossiers_consultations_id` INT NOT NULL,
  `observation` TEXT NULL,
  `resultat_exam_comp` TEXT NULL,
  `date_creation` DATETIME NULL,
  `user_id` INT NOT NULL,
  `form_type_id` INT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_consultations_dossiers_cosultations1_idx` (`dossiers_consultations_id` ASC),
  INDEX `fk_consultations_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_consultations_dossiers_cosultations1`
    FOREIGN KEY (`dossiers_consultations_id`)
    REFERENCES `target_db`.`dossiers_consultations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consultations_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`type_assurance`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`type_assurance` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(150) NULL,
  `taux` FLOAT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`prestations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`prestations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `patients_id` INT NOT NULL,
  `date` DATETIME NOT NULL,
  `montant_normal` FLOAT NULL,
  `montant_patient` FLOAT NULL,
  `montant_restant` FLOAT NULL,
  `num_quittance` VARCHAR(45) NULL,
  `etat` TINYINT NULL DEFAULT 1,
  `user_id` INT NOT NULL,
  `type_assurance_id` INT NULL,
  `type_assurance_taux` FLOAT NULL,
  `numero` VARCHAR(45) NULL,
  `ogd` VARCHAR(45) NULL,
  `beneficiaire` VARCHAR(45) NULL,
  `date_annulation` DATETIME NULL,
  `motif_annulation` VARCHAR(45) NULL,
  `user_id_annulation` INT NULL,
  `montant_recu` FLOAT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_prestations_patients1_idx` (`patients_id` ASC),
  INDEX `fk_prestations_user1_idx` (`user_id` ASC),
  INDEX `fk_prestations_type_assurance1_idx` (`type_assurance_id` ASC),
  INDEX `fk_prestations_user2_idx` (`user_id_annulation` ASC),
  CONSTRAINT `fk_prestations_patients1`
    FOREIGN KEY (`patients_id`)
    REFERENCES `target_db`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestations_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestations_type_assurance1`
    FOREIGN KEY (`type_assurance_id`)
    REFERENCES `target_db`.`type_assurance` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestations_user2`
    FOREIGN KEY (`user_id_annulation`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`prestations_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`prestations_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `prestations_id` INT NOT NULL,
  `actes_id` INT NOT NULL,
  `montant_normal` FLOAT NULL,
  `montant_unitaire` FLOAT NULL,
  `montant_patient` FLOAT NULL,
  `montant_restant` FLOAT NULL,
  `quantite` TINYINT NULL,
  `user_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_prestations_details_prestations1_idx` (`prestations_id` ASC),
  INDEX `fk_prestations_details_actes1_idx` (`actes_id` ASC),
  INDEX `fk_prestations_details_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_prestations_details_prestations1`
    FOREIGN KEY (`prestations_id`)
    REFERENCES `target_db`.`prestations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestations_details_actes1`
    FOREIGN KEY (`actes_id`)
    REFERENCES `target_db`.`actes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestations_details_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`consultations_examen`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`consultations_examen` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `actes_id` INT NOT NULL,
  `consultations_id` INT NULL,
  `dossiers_consultations_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_consultation_examen_consultations1_idx` (`consultations_id` ASC),
  INDEX `fk_consultations_examen_dossiers_consultations1_idx` (`dossiers_consultations_id` ASC),
  INDEX `fk_consultations_examen_actes1_idx` (`actes_id` ASC),
  CONSTRAINT `fk_consultation_examen_consultations1`
    FOREIGN KEY (`consultations_id`)
    REFERENCES `target_db`.`consultations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consultations_examen_dossiers_consultations1`
    FOREIGN KEY (`dossiers_consultations_id`)
    REFERENCES `target_db`.`dossiers_consultations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consultations_examen_actes1`
    FOREIGN KEY (`actes_id`)
    REFERENCES `target_db`.`actes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`consultations_prescriptions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`consultations_prescriptions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `medicament` VARCHAR(200) NULL,
  `medicament_id` VARCHAR(10) NULL,
  `quantite` FLOAT NULL,
  `mode` VARCHAR(45) NULL,
  `posologie` VARCHAR(45) NULL,
  `consultations_id` INT NULL,
  `dossiers_consultations_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_consultation_prescriptions_consultations1_idx` (`consultations_id` ASC),
  INDEX `fk_consultations_prescriptions_dossiers_consultations1_idx` (`dossiers_consultations_id` ASC),
  CONSTRAINT `fk_consultation_prescriptions_consultations1`
    FOREIGN KEY (`consultations_id`)
    REFERENCES `target_db`.`consultations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consultations_prescriptions_dossiers_consultations1`
    FOREIGN KEY (`dossiers_consultations_id`)
    REFERENCES `target_db`.`dossiers_consultations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`patients_antecedant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`patients_antecedant` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` ENUM('Medicaux', 'Chirurgicaux', 'Gyneco-obstétrique', 'Familiaux') NULL,
  `libelle` VARCHAR(255) NULL,
  `patients_id` INT NOT NULL,
  `niveau` ENUM('normal', 'moyen', 'important') NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_patients_antecedant_patients1_idx` (`patients_id` ASC),
  CONSTRAINT `fk_patients_antecedant_patients1`
    FOREIGN KEY (`patients_id`)
    REFERENCES `target_db`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`consultations_constantes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`consultations_constantes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cle` VARCHAR(45) NULL,
  `valeur` VARCHAR(45) NULL,
  `consultations_id` INT NULL,
  `dossiers_consultations_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_consultation_constantes_consultations1_idx` (`consultations_id` ASC),
  INDEX `fk_consultations_constantes_dossiers_consultations1_idx` (`dossiers_consultations_id` ASC),
  CONSTRAINT `fk_consultation_constantes_consultations1`
    FOREIGN KEY (`consultations_id`)
    REFERENCES `target_db`.`consultations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consultations_constantes_dossiers_consultations1`
    FOREIGN KEY (`dossiers_consultations_id`)
    REFERENCES `target_db`.`dossiers_consultations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`patients_assurance`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`patients_assurance` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `patients_id` INT NOT NULL,
  `type_assurance_id` INT NOT NULL,
  `numero` VARCHAR(45) NULL,
  `ogd` ENUM('inps', 'cmss') NULL,
  `beneficiaire` ENUM('titulaire', 'enfant', 'parent') NULL,
  `autres_infos` VARCHAR(200) NULL,
  `default` TINYINT NULL,
  INDEX `fk_patient_assurance_patients1_idx` (`patients_id` ASC),
  INDEX `fk_patient_assurance_type_assurance1_idx` (`type_assurance_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_patient_assurance_patients1`
    FOREIGN KEY (`patients_id`)
    REFERENCES `target_db`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_patient_assurance_type_assurance1`
    FOREIGN KEY (`type_assurance_id`)
    REFERENCES `target_db`.`type_assurance` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`consultation_liste_attente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`consultation_liste_attente` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME NULL,
  `prestations_details_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `patients_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_consultation_liste_attente_prestations_details1_idx` (`prestations_details_id` ASC),
  INDEX `fk_consultation_liste_attente_user1_idx` (`user_id` ASC),
  INDEX `fk_consultation_liste_attente_patients1_idx` (`patients_id` ASC),
  CONSTRAINT `fk_consultation_liste_attente_prestations_details1`
    FOREIGN KEY (`prestations_details_id`)
    REFERENCES `target_db`.`prestations_details` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consultation_liste_attente_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consultation_liste_attente_patients1`
    FOREIGN KEY (`patients_id`)
    REFERENCES `target_db`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`forme_produit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`forme_produit` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`type_produit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`type_produit` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(70) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`classe_therapeutique`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`classe_therapeutique` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`produit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`produit` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(75) NULL,
  `type_produit_id` INT NULL,
  `forme_produit_id` INT NULL,
  `classe_therapeutique_id` INT NULL,
  `unite_vente` VARCHAR(50) NULL,
  `presentation` VARCHAR(55) NULL,
  `dosage` VARCHAR(10) NULL,
  `seuil_min` INT NULL,
  `seuil_max` INT NULL,
  `prix` FLOAT NULL,
  `stock` INT NULL DEFAULT 0,
  `etat` ENUM('actif', 'inactif') NULL,
  INDEX `fk_produit_forme_produit1_idx` (`forme_produit_id` ASC),
  PRIMARY KEY (`id`),
  INDEX `fk_produit_type_produit1_idx` (`type_produit_id` ASC),
  INDEX `fk_produit_classe_therapeutique1_idx` (`classe_therapeutique_id` ASC),
  CONSTRAINT `fk_produit_forme_produit1`
    FOREIGN KEY (`forme_produit_id`)
    REFERENCES `target_db`.`forme_produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produit_type_produit1`
    FOREIGN KEY (`type_produit_id`)
    REFERENCES `target_db`.`type_produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produit_classe_therapeutique1`
    FOREIGN KEY (`classe_therapeutique_id`)
    REFERENCES `target_db`.`classe_therapeutique` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`cim10`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`cim10` (
  `LID` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `SID` BIGINT(20) NOT NULL DEFAULT '0',
  `source` CHAR(1) NOT NULL DEFAULT '',
  `valid` TINYINT(4) NOT NULL DEFAULT '0',
  `libelle` VARCHAR(255) NOT NULL DEFAULT '',
  `FR_OMS` VARCHAR(255) NOT NULL DEFAULT '',
  `EN_OMS` VARCHAR(255) NOT NULL DEFAULT '',
  `GE_DIMDI` VARCHAR(255) NOT NULL DEFAULT '',
  `GE_AUTO` VARCHAR(255) NOT NULL DEFAULT '',
  `FR_CHRONOS` VARCHAR(255) NOT NULL DEFAULT '',
  `date` DATETIME NULL,
  `author` VARCHAR(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`LID`),
  INDEX `SID` (`SID` ASC),
  INDEX `SID_2` (`SID` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 7919
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `target_db`.`point_distribution`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`point_distribution` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NULL,
  `type` ENUM('stockage', 'vente', 'relais', 'autre') NULL,
  `default` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`fournisseur`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`fournisseur` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(70) NULL,
  `telephone` VARCHAR(45) NULL,
  `adresse` VARCHAR(100) NULL,
  `email` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`commande`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`commande` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `objet` VARCHAR(45) NULL,
  `date` DATE NULL,
  `etat` ENUM('creation', 'reception', 'cloture') NULL,
  `fournisseur_id` INT NOT NULL,
  `montant` DOUBLE NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_commande_fournisseur1_idx` (`fournisseur_id` ASC),
  CONSTRAINT `fk_commande_fournisseur1`
    FOREIGN KEY (`fournisseur_id`)
    REFERENCES `target_db`.`fournisseur` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`reception`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`reception` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `objet` VARCHAR(200) NULL,
  `date` DATE NULL,
  `etat` ENUM('encours', 'cloture') NULL,
  `fournisseur_id` INT NULL,
  `commande_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_reception_fournisseur1_idx` (`fournisseur_id` ASC),
  INDEX `fk_reception_commande1_idx` (`commande_id` ASC),
  CONSTRAINT `fk_reception_fournisseur1`
    FOREIGN KEY (`fournisseur_id`)
    REFERENCES `target_db`.`fournisseur` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reception_commande1`
    FOREIGN KEY (`commande_id`)
    REFERENCES `target_db`.`commande` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`approvisionnement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`approvisionnement` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `produit_id` INT NOT NULL,
  `point_distribution_id` INT NOT NULL,
  `quantite` INT NULL,
  `lot` VARCHAR(20) NULL,
  `date_peremption` DATE NULL,
  `date` DATETIME NULL,
  `motif` VARCHAR(255) NULL,
  `reception_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_approvisionnement_details_produit1_idx` (`produit_id` ASC),
  INDEX `fk_approvisionnement_details_point_distribution1_idx` (`point_distribution_id` ASC),
  INDEX `fk_approvisionnement_reception1_idx` (`reception_id` ASC),
  CONSTRAINT `fk_approvisionnement_details_produit1`
    FOREIGN KEY (`produit_id`)
    REFERENCES `target_db`.`produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_approvisionnement_details_point_distribution1`
    FOREIGN KEY (`point_distribution_id`)
    REFERENCES `target_db`.`point_distribution` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_approvisionnement_reception1`
    FOREIGN KEY (`reception_id`)
    REFERENCES `target_db`.`reception` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`stock_point_distribution`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`stock_point_distribution` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lot` VARCHAR(20) NULL,
  `date_peremption` DATE NULL,
  `point_distribution_id` INT NOT NULL,
  `stock` INT NULL,
  `reste` INT NULL DEFAULT 0,
  `produit_id` INT NOT NULL,
  `approvisionnement_details_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stock_produit_point_distribution1_idx` (`point_distribution_id` ASC),
  INDEX `fk_stock_produit_produit1_idx` (`produit_id` ASC),
  INDEX `fk_stock_point_de_vente_approvisionnement_details1_idx` (`approvisionnement_details_id` ASC),
  CONSTRAINT `fk_stock_produit_point_distribution1`
    FOREIGN KEY (`point_distribution_id`)
    REFERENCES `target_db`.`point_distribution` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_produit_produit1`
    FOREIGN KEY (`produit_id`)
    REFERENCES `target_db`.`produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_point_de_vente_approvisionnement_details1`
    FOREIGN KEY (`approvisionnement_details_id`)
    REFERENCES `target_db`.`approvisionnement` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`point_distribution_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`point_distribution_user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `point_distribution_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_point_distribution_user_user1_idx` (`user_id` ASC),
  INDEX `fk_point_distribution_user_point_distribution1_idx` (`point_distribution_id` ASC),
  CONSTRAINT `fk_point_distribution_user_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_point_distribution_user_point_distribution1`
    FOREIGN KEY (`point_distribution_id`)
    REFERENCES `target_db`.`point_distribution` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`transaction_produit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`transaction_produit` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `created` DATETIME NULL,
  `quantite` INT NULL,
  `type` ENUM('vente', 'appro', 'inventaire', 'perte', 'ajout', 'distribution') NULL,
  `produit_id` INT NOT NULL,
  `point_distribution_id` INT NULL,
  `stock_g_avant` INT NULL,
  `stock_g_apres` INT NULL,
  `stock_pv_avant` INT NULL,
  `stock_pv_apres` INT NULL,
  `lot` VARCHAR(20) NULL,
  `date_peremption` DATE NULL,
  `operation` ENUM('a', 's') NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_transaction_produit_produit1_idx` (`produit_id` ASC),
  INDEX `fk_transaction_produit_point_distribution1_idx` (`point_distribution_id` ASC),
  CONSTRAINT `fk_transaction_produit_produit1`
    FOREIGN KEY (`produit_id`)
    REFERENCES `target_db`.`produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_transaction_produit_point_distribution1`
    FOREIGN KEY (`point_distribution_id`)
    REFERENCES `target_db`.`point_distribution` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`reception_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`reception_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `quantite` INT NULL,
  `litige` INT NULL,
  `manquant` INT NULL,
  `observation` VARCHAR(45) NULL,
  `lot` VARCHAR(20) NULL,
  `date_peremption` DATE NULL,
  `prix_achat` FLOAT NULL,
  `coef` FLOAT NULL,
  `prix_vente` FLOAT NULL,
  `reception_id` INT NOT NULL,
  `produit_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_reception_details_reception1_idx` (`reception_id` ASC),
  INDEX `fk_reception_details_produit1_idx` (`produit_id` ASC),
  CONSTRAINT `fk_reception_details_reception1`
    FOREIGN KEY (`reception_id`)
    REFERENCES `target_db`.`reception` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reception_details_produit1`
    FOREIGN KEY (`produit_id`)
    REFERENCES `target_db`.`produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`commande_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`commande_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `quantite` INT NULL,
  `quantite_livree` INT NULL DEFAULT 0,
  `prix` FLOAT NULL,
  `commande_id` INT NOT NULL,
  `produit_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_commande_details_commande1_idx` (`commande_id` ASC),
  INDEX `fk_commande_details_produit1_idx` (`produit_id` ASC),
  CONSTRAINT `fk_commande_details_commande1`
    FOREIGN KEY (`commande_id`)
    REFERENCES `target_db`.`commande` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_commande_details_produit1`
    FOREIGN KEY (`produit_id`)
    REFERENCES `target_db`.`produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`ajustement_motifs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`ajustement_motifs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(100) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`ajustement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`ajustement` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME NULL,
  `type` ENUM('ajout', 'perte') NULL,
  `quantite` INT NULL,
  `lot` VARCHAR(45) NULL,
  `date_peremption` DATE NULL,
  `produit_id` INT NOT NULL,
  `point_distribution_id` INT NOT NULL,
  `ajustement_motifs_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ajustement_produit1_idx` (`produit_id` ASC),
  INDEX `fk_ajustement_point_distribution1_idx` (`point_distribution_id` ASC),
  INDEX `fk_ajustement_ajustement_motifs1_idx` (`ajustement_motifs_id` ASC),
  CONSTRAINT `fk_ajustement_produit1`
    FOREIGN KEY (`produit_id`)
    REFERENCES `target_db`.`produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ajustement_point_distribution1`
    FOREIGN KEY (`point_distribution_id`)
    REFERENCES `target_db`.`point_distribution` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ajustement_ajustement_motifs1`
    FOREIGN KEY (`ajustement_motifs_id`)
    REFERENCES `target_db`.`ajustement_motifs` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`recu_medicament`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`recu_medicament` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `montant_normal` FLOAT NULL,
  `montant_patient` FLOAT NULL,
  `montant_restant` FLOAT NULL,
  `num_ordonnance` VARCHAR(45) NULL,
  `etat` TINYINT NULL DEFAULT 1,
  `type_assurance_taux` FLOAT NULL,
  `date_annulation` DATETIME NULL,
  `motif_annulation` VARCHAR(45) NULL,
  `montant_recu` FLOAT NULL,
  `patients_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `user_annulation_id` INT NULL,
  `type_assurance_id` INT NULL,
  `numero` VARCHAR(45) NULL,
  `ogd` VARCHAR(45) NULL,
  `beneficiaire` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_recu_medicament_patients1_idx` (`patients_id` ASC),
  INDEX `fk_recu_medicament_user1_idx` (`user_id` ASC),
  INDEX `fk_recu_medicament_user2_idx` (`user_annulation_id` ASC),
  INDEX `fk_recu_medicament_type_assurance1_idx` (`type_assurance_id` ASC),
  CONSTRAINT `fk_recu_medicament_patients1`
    FOREIGN KEY (`patients_id`)
    REFERENCES `target_db`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recu_medicament_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recu_medicament_user2`
    FOREIGN KEY (`user_annulation_id`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recu_medicament_type_assurance1`
    FOREIGN KEY (`type_assurance_id`)
    REFERENCES `target_db`.`type_assurance` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`recu_medicament_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`recu_medicament_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `montant_normal` FLOAT NULL,
  `montant_unitaire` FLOAT NULL,
  `montant_patient` FLOAT NULL,
  `montant_restant` FLOAT NULL,
  `quantite` TINYINT NULL,
  `recu_medicament_id` INT NOT NULL,
  `produit_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_recu_medicament_details_recu_medicament1_idx` (`recu_medicament_id` ASC),
  INDEX `fk_recu_medicament_details_produit1_idx` (`produit_id` ASC),
  CONSTRAINT `fk_recu_medicament_details_recu_medicament1`
    FOREIGN KEY (`recu_medicament_id`)
    REFERENCES `target_db`.`recu_medicament` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recu_medicament_details_produit1`
    FOREIGN KEY (`produit_id`)
    REFERENCES `target_db`.`produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`inventaire`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`inventaire` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `objet` VARCHAR(100) NULL,
  `date` DATE NULL,
  `debut` DATE NULL,
  `fin` DATE NULL,
  `etat` ENUM('encours', 'cloture') NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`inventaire_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`inventaire_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `produit_id` INT NOT NULL,
  `inventaire_id` INT NOT NULL,
  `initial` INT NULL,
  `entre` INT NULL,
  `sortie` INT NULL,
  `theorique` INT NULL,
  `physique` INT NULL,
  `perte` INT NULL,
  `ajout` INT NULL,
  `observation` VARCHAR(100) NULL,
  `details` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_inventaire_detail_produit1_idx` (`produit_id` ASC),
  INDEX `fk_inventaire_detail_inventaire1_idx` (`inventaire_id` ASC),
  CONSTRAINT `fk_inventaire_detail_produit1`
    FOREIGN KEY (`produit_id`)
    REFERENCES `target_db`.`produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inventaire_detail_inventaire1`
    FOREIGN KEY (`inventaire_id`)
    REFERENCES `target_db`.`inventaire` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`rupture_stock`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`rupture_stock` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date_rupture` DATE NOT NULL,
  `date_appro` DATE NULL,
  `produit_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_rupture_stock_produit1_idx` (`produit_id` ASC),
  CONSTRAINT `fk_rupture_stock_produit1`
    FOREIGN KEY (`produit_id`)
    REFERENCES `target_db`.`produit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`f_compte`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`f_compte` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `numero` VARCHAR(15) NULL,
  `libelle` VARCHAR(200) NULL,
  `type` ENUM('depense', 'recette') NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`f_sous_compte`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`f_sous_compte` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `numero` VARCHAR(15) NULL,
  `libelle` VARCHAR(200) NULL,
  `f_compte_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_f_sous_compte_f_compte1_idx` (`f_compte_id` ASC),
  CONSTRAINT `fk_f_sous_compte_f_compte1`
    FOREIGN KEY (`f_compte_id`)
    REFERENCES `target_db`.`f_compte` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`f_banque`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`f_banque` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`f_banque_compte`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`f_banque_compte` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `compte` VARCHAR(45) NULL,
  `f_banque_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_f_banque_compte_f_banque1_idx` (`f_banque_id` ASC),
  CONSTRAINT `fk_f_banque_compte_f_banque1`
    FOREIGN KEY (`f_banque_id`)
    REFERENCES `target_db`.`f_banque` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`f_operation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`f_operation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `f_sous_compte_id` INT NOT NULL,
  `montant` DOUBLE NULL,
  `f_banque_compte_id` INT NULL,
  `details` VARCHAR(255) NULL,
  `date` DATE NULL,
  `banque_cheque` VARCHAR(20) NULL,
  `banque_porteur` VARCHAR(100) NULL,
  `banque_details` VARCHAR(255) NULL,
  `user_id` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `fk_f_operation_f_sous_compte1_idx` (`f_sous_compte_id` ASC),
  INDEX `fk_f_operation_f_banque_compte1_idx` (`f_banque_compte_id` ASC),
  INDEX `fk_f_operation_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_f_operation_f_sous_compte1`
    FOREIGN KEY (`f_sous_compte_id`)
    REFERENCES `target_db`.`f_sous_compte` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_f_operation_f_banque_compte1`
    FOREIGN KEY (`f_banque_compte_id`)
    REFERENCES `target_db`.`f_banque_compte` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_f_operation_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`f_planification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`f_planification` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type_prevision` ENUM('Activité', 'Subvention', 'Dépense') NULL,
  `montant` DOUBLE NULL,
  `quantite` INT NULL,
  `prix_unitaire` DOUBLE NULL,
  `annee` YEAR NULL,
  `f_sous_compte_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_f_planification_f_sous_compte1_idx` (`f_sous_compte_id` ASC),
  CONSTRAINT `fk_f_planification_f_sous_compte1`
    FOREIGN KEY (`f_sous_compte_id`)
    REFERENCES `target_db`.`f_sous_compte` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`f_designation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`f_designation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(200) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`f_operation_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`f_operation_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `quantite` VARCHAR(45) NULL,
  `prix_unitaire` DOUBLE NULL,
  `montant` VARCHAR(45) NULL,
  `f_operation_id` INT NOT NULL,
  `f_designation_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tf_operation_details_f_operation1_idx` (`f_operation_id` ASC),
  INDEX `fk_tf_operation_details_f_designation1_idx` (`f_designation_id` ASC),
  CONSTRAINT `fk_tf_operation_details_f_operation1`
    FOREIGN KEY (`f_operation_id`)
    REFERENCES `target_db`.`f_operation` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tf_operation_details_f_designation1`
    FOREIGN KEY (`f_designation_id`)
    REFERENCES `target_db`.`f_designation` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`f_solde`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`f_solde` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `montant` DOUBLE NULL,
  `f_banque_compte_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_f_solde_f_banque_compte1_idx` (`f_banque_compte_id` ASC),
  CONSTRAINT `fk_f_solde_f_banque_compte1`
    FOREIGN KEY (`f_banque_compte_id`)
    REFERENCES `target_db`.`f_banque_compte` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`parametrage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`parametrage` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(100) NULL,
  `adresse` VARCHAR(45) NULL,
  `telephone` VARCHAR(45) NULL,
  `logo` VARCHAR(45) NULL,
  `pharmacie_type` VARCHAR(45) NULL,
  `default_lot` VARCHAR(45) NULL,
  `default_peremption` DATE NULL,
  `default_coef` VARCHAR(3) NULL,
  `default_constante` VARCHAR(255) NULL,
  `default_examen` VARCHAR(255) NULL,
  `type_entete` ENUM('e', 'l') NULL,
  `ligne1` VARCHAR(150) NULL,
  `ligne2` VARCHAR(150) NULL,
  `ligne3` VARCHAR(150) NULL,
  `ligne4` VARCHAR(150) NULL,
  `template_logo` ENUM('left', 'right', 'top') NULL,
  `img_msg_annonce` VARCHAR(255) NULL,
  `img_msg_fin` VARCHAR(255) NULL,
  `diagnostic_source` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`labo_categories_analyse`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`labo_categories_analyse` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(100) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`labo_analyses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`labo_analyses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(100) NOT NULL,
  `code` VARCHAR(10) NULL,
  `unite` VARCHAR(100) NULL,
  `type_valeur` ENUM('n', 'a', 'm') NULL,
  `valeur_possible` VARCHAR(150) NULL,
  `norme` VARCHAR(200) NULL,
  `labo_categories_analyse_id` INT NULL,
  `childs_id` VARCHAR(255) NULL,
  `position` INT NULL,
  `has_antibiogramme` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_labo_analyses_labo_categories_analyse1_idx` (`labo_categories_analyse_id` ASC),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC),
  CONSTRAINT `fk_labo_analyses_labo_categories_analyse1`
    FOREIGN KEY (`labo_categories_analyse_id`)
    REFERENCES `target_db`.`labo_categories_analyse` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`labo_demandes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`labo_demandes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME NULL,
  `close_date` DATETIME NULL,
  `paillasse` VARCHAR(45) NULL,
  `patients_id` INT NOT NULL,
  `prestations_id` INT NULL,
  `sequence` TINYINT(1) NULL,
  `etat` ENUM('encours', 'clotûré', 'création') NULL,
  `provenance` VARCHAR(100) NULL,
  `prescripteur` VARCHAR(100) NULL,
  `histoire` VARCHAR(100) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_labo_demande_patients1_idx` (`patients_id` ASC),
  INDEX `fk_labo_demande_prestations1_idx` (`prestations_id` ASC),
  CONSTRAINT `fk_labo_demande_patients1`
    FOREIGN KEY (`patients_id`)
    REFERENCES `target_db`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_labo_demande_prestations1`
    FOREIGN KEY (`prestations_id`)
    REFERENCES `target_db`.`prestations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`labo_demandes_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`labo_demandes_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `labo_demandes_id` INT NOT NULL,
  `labo_analyses_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_labo_demande_details_labo_demandes1_idx` (`labo_demandes_id` ASC),
  INDEX `fk_labo_demandes_details_labo_analyses1_idx` (`labo_analyses_id` ASC),
  CONSTRAINT `fk_labo_demande_details_labo_demandes1`
    FOREIGN KEY (`labo_demandes_id`)
    REFERENCES `target_db`.`labo_demandes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_labo_demandes_details_labo_analyses1`
    FOREIGN KEY (`labo_analyses_id`)
    REFERENCES `target_db`.`labo_analyses` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`labo_antibiogrammes_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`labo_antibiogrammes_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(100) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`labo_antibiogrammes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`labo_antibiogrammes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(100) NULL,
  `labo_antibiogrammes_type_id` INT NOT NULL,
  `antibiotiques` VARCHAR(100) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_labo_antibiogrammes_labo_antibiogrammes_type1_idx` (`labo_antibiogrammes_type_id` ASC),
  CONSTRAINT `fk_labo_antibiogrammes_labo_antibiogrammes_type1`
    FOREIGN KEY (`labo_antibiogrammes_type_id`)
    REFERENCES `target_db`.`labo_antibiogrammes_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`labo_antibiotiques`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`labo_antibiotiques` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(45) NULL,
  `libelle` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`labo_demandes_resultats`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`labo_demandes_resultats` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `valeur` VARCHAR(100) NULL,
  `unite` VARCHAR(100) NULL,
  `labo_demandes_id` INT NOT NULL,
  `labo_analyses_id` INT NOT NULL,
  `antibiogramme` TEXT NULL,
  `etat` ENUM('0', '1') NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `fk_labo_demandes_resultats_labo_demandes1_idx` (`labo_demandes_id` ASC),
  INDEX `fk_labo_demandes_resultats_labo_analyses1_idx` (`labo_analyses_id` ASC),
  CONSTRAINT `fk_labo_demandes_resultats_labo_demandes1`
    FOREIGN KEY (`labo_demandes_id`)
    REFERENCES `target_db`.`labo_demandes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_labo_demandes_resultats_labo_analyses1`
    FOREIGN KEY (`labo_analyses_id`)
    REFERENCES `target_db`.`labo_analyses` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`img_demandes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`img_demandes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME NULL,
  `close_date` DATETIME NULL,
  `etat` ENUM('encours', 'clotûré', 'création') NULL,
  `provenance` VARCHAR(100) NULL,
  `prescripteur` VARCHAR(100) NULL,
  `indication` VARCHAR(255) NULL,
  `patients_id` INT NOT NULL,
  `prestations_id` INT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_img_demandes_patients1_idx` (`patients_id` ASC),
  INDEX `fk_img_demandes_prestations1_idx` (`prestations_id` ASC),
  INDEX `fk_img_demandes_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_img_demandes_patients1`
    FOREIGN KEY (`patients_id`)
    REFERENCES `target_db`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_img_demandes_prestations1`
    FOREIGN KEY (`prestations_id`)
    REFERENCES `target_db`.`prestations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_img_demandes_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `target_db`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`img_items_categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`img_items_categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(200) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`img_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`img_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(200) NULL,
  `code` VARCHAR(45) NULL,
  `img_items_categories_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_img_item_img_items_categories1_idx` (`img_items_categories_id` ASC),
  CONSTRAINT `fk_img_item_img_items_categories1`
    FOREIGN KEY (`img_items_categories_id`)
    REFERENCES `target_db`.`img_items_categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`img_demandes_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`img_demandes_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `protocole` TEXT NULL,
  `interpretation` TEXT NULL,
  `conclusion` TEXT NULL,
  `img_demandes_id` INT NOT NULL,
  `img_items_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_img_demandes_details_img_demandes1_idx` (`img_demandes_id` ASC),
  INDEX `fk_img_demandes_details_img_items1_idx` (`img_items_id` ASC),
  CONSTRAINT `fk_img_demandes_details_img_demandes1`
    FOREIGN KEY (`img_demandes_id`)
    REFERENCES `target_db`.`img_demandes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_img_demandes_details_img_items1`
    FOREIGN KEY (`img_items_id`)
    REFERENCES `target_db`.`img_items` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`forms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`forms` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(150) NULL,
  `code` VARCHAR(100) NULL,
  `type` ENUM('base', 'onglet') NULL,
  `forms_assoc` VARCHAR(100) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`forms_elements`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`forms_elements` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(150) NULL,
  `position` TINYINT NULL,
  `type_valeur` ENUM('n', 'an', 'c', 'm', 'd') NULL,
  `valeur_possible` VARCHAR(150) NULL,
  `forms_id` INT NOT NULL,
  `place_after_c` VARCHAR(100) NULL,
  `place_after_s` VARCHAR(100) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_forms_elements_forms1_idx` (`forms_id` ASC),
  CONSTRAINT `fk_forms_elements_forms1`
    FOREIGN KEY (`forms_id`)
    REFERENCES `target_db`.`forms` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`forms_results`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`forms_results` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `valeur` TEXT NULL,
  `entity_type` ENUM('c', 's') NULL,
  `entity_id` INT NULL,
  `forms_elements_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_forms_results_forms_elements1_idx` (`forms_elements_id` ASC),
  CONSTRAINT `fk_forms_results_forms_elements1`
    FOREIGN KEY (`forms_elements_id`)
    REFERENCES `target_db`.`forms_elements` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`img_modele`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`img_modele` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `interpretation` TEXT NULL,
  `conclusion` TEXT NULL,
  `keyword` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`diagnostic_source`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`diagnostic_source` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(200) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`pharmacie_work_flow`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`pharmacie_work_flow` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `not_available` VARCHAR(255) NULL,
  `patients_id` INT NOT NULL,
  `available` VARCHAR(100) NULL,
  `entity_type` ENUM('c', 's') NULL,
  `entity_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_pharmacie_work_flow_patients1_idx` (`patients_id` ASC),
  CONSTRAINT `fk_pharmacie_work_flow_patients1`
    FOREIGN KEY (`patients_id`)
    REFERENCES `target_db`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`cs_motifs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`cs_motifs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`consultations_motifs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`consultations_motifs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dossiers_consultations_id` INT NOT NULL,
  `cs_motifs_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_consultations_motifs_dossiers_consultations1_idx` (`dossiers_consultations_id` ASC),
  INDEX `fk_consultations_motifs_cs_motifs1_idx` (`cs_motifs_id` ASC),
  CONSTRAINT `fk_consultations_motifs_dossiers_consultations1`
    FOREIGN KEY (`dossiers_consultations_id`)
    REFERENCES `target_db`.`dossiers_consultations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consultations_motifs_cs_motifs1`
    FOREIGN KEY (`cs_motifs_id`)
    REFERENCES `target_db`.`cs_motifs` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `target_db`.`consultations_diagnostics`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `target_db`.`consultations_diagnostics` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NULL,
  `type` ENUM('h', 'd') NULL,
  `dossiers_consultations_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_consultations_diagnostics_dossiers_consultations1_idx` (`dossiers_consultations_id` ASC),
  CONSTRAINT `fk_consultations_diagnostics_dossiers_consultations1`
    FOREIGN KEY (`dossiers_consultations_id`)
    REFERENCES `target_db`.`dossiers_consultations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- --------------------------------------------------------------
-- DATA
-- --------------------------------------------------------------

INSERT INTO `target_db`.`parametrage` (`id`, `nom`, `adresse`, `telephone`, `logo`, `pharmacie_type`, `default_lot`, `default_peremption`, `default_coef`, `default_constante`, `default_examen`, `type_entete`, `ligne1`, `ligne2`, `ligne3`, `ligne4`, `template_logo`, `img_msg_annonce`, `img_msg_fin`, `diagnostic_source`) VALUES
(1, 'Nom du centre', '----', '0022377777777', '', '1', '2312', '2018-09-01', '1', 'TA,FC,Temperature', '', 'l', '-----', 'l2', 'BP: E3791', 'l4', 'right', 'Chere confrere, merci de nous avoir adresser votre patient', '----------', 'ajaxDiagnostic');


INSERT INTO `target_db`.`residence` (`id`, `libelle`) VALUES
(1, 'Non definie');

INSERT INTO `target_db`.`services` (`id`, `libelle`) VALUES
(1, 'service général');

INSERT INTO `target_db`.`user` (`id`, `email`, `nom`, `prenom`, `telephone`, `login`, `password`, `profile`, `permissions`, `status`, `services_id`, `prestataire`, `forms_assoc`) VALUES
(1, 'admin@admin.com', 'Admin', 'admin', '12-55-23-55', 'admin', '9cf95dacd226dcf43da376cdb6cbba7035218921', 'medecin', '["caisse_w","dp_w","cs_w","ph_w","f_w","conf_w"]', 'actif', 1, 1, NULL);
