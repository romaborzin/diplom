-- MySQL Script generated by MySQL Workbench
-- Mon Dec  3 22:05:50 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema diplom
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema diplom
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `diplom` DEFAULT CHARACTER SET utf8 ;
USE `diplom` ;

-- -----------------------------------------------------
-- Table `diplom`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `diplom`.`user` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NULL,
  `login` VARCHAR(255) NULL,
  `pass` VARCHAR(60) NULL,
  `first_name` VARCHAR(127) NULL,
  `second_name` VARCHAR(45) NULL,
  PRIMARY KEY (`user_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `diplom`.`room`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `diplom`.`room` (
  `room_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `description` VARCHAR(1023) NULL,
  `datetime` DATETIME NULL,
  `type` ENUM("talks", "conference") NULL,
  PRIMARY KEY (`room_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `diplom`.`user_has_room`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `diplom`.`user_has_room` (
  `user_user_id` INT(11) NOT NULL,
  `room_room_id` INT(11) NOT NULL,
  `role` ENUM("guest", "speaker", "manager") NULL,
  PRIMARY KEY (`user_user_id`, `room_room_id`),
  INDEX `fk_user_has_conference_conference1_idx` (`room_room_id` ASC),
  INDEX `fk_user_has_conference_user_idx` (`user_user_id` ASC),
  CONSTRAINT `fk_user_has_conference_user`
    FOREIGN KEY (`user_user_id`)
    REFERENCES `diplom`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_conference_conference1`
    FOREIGN KEY (`room_room_id`)
    REFERENCES `diplom`.`room` (`room_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `diplom`.`message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `diplom`.`message` (
  `message_id` INT(11) NOT NULL AUTO_INCREMENT,
  `data_time` DATETIME NULL,
  `text` VARCHAR(45) NULL,
  PRIMARY KEY (`message_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `diplom`.`conference_has_message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `diplom`.`conference_has_message` (
  `room_room_id` INT(11) NOT NULL,
  `message_message_id` INT(11) NOT NULL,
  PRIMARY KEY (`room_room_id`, `message_message_id`),
  INDEX `fk_conference_has_message_message1_idx` (`message_message_id` ASC),
  INDEX `fk_conference_has_message_conference1_idx` (`room_room_id` ASC),
  CONSTRAINT `fk_conference_has_message_conference1`
    FOREIGN KEY (`room_room_id`)
    REFERENCES `diplom`.`room` (`room_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_conference_has_message_message1`
    FOREIGN KEY (`message_message_id`)
    REFERENCES `diplom`.`message` (`message_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
