SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`status`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`status` (
  `codigostatus` VARCHAR(45) NOT NULL ,
  `nomestatus` VARCHAR(45) NULL ,
  `descricaostatus` VARCHAR(45) NULL ,
  PRIMARY KEY (`codigostatus`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`sessao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`sessao` (
  `idsessao` INT NOT NULL ,
  `browsersessao` VARCHAR(45) NULL ,
  `ipsessao` VARCHAR(45) NULL ,
  PRIMARY KEY (`idsessao`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`categoria`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`categoria` (
  `grupocategoria` INT NOT NULL ,
  `nomecategoria` VARCHAR(45) NULL ,
  `descricaocategoria` VARCHAR(45) NULL ,
  PRIMARY KEY (`grupocategoria`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`produto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`produto` (
  `skuproduto` INT NOT NULL ,
  `nomeproduto` VARCHAR(45) NULL ,
  `precoproduto` VARCHAR(45) NULL ,
  `descricaoproduto` VARCHAR(45) NULL ,
  `imagemproduto` VARCHAR(45) NULL ,
  `quantidadeestoqueproduto` VARCHAR(45) NULL ,
  `grupocategoria` INT NOT NULL ,
  PRIMARY KEY (`skuproduto`, `grupocategoria`) ,
  INDEX `grupocategoriafkidx` (`grupocategoria` ASC) ,
  CONSTRAINT `grupocategoriafk`
    FOREIGN KEY (`grupocategoria` )
    REFERENCES `mydb`.`categoria` (`grupocategoria` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`administrador`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`administrador` (
  `emailadministrador` INT NOT NULL ,
  `nomeadministrador` VARCHAR(45) NULL ,
  `sobrenomeadministrador` VARCHAR(45) NULL ,
  `senhaadministrador` VARCHAR(45) NULL ,
  PRIMARY KEY (`emailadministrador`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`transacao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`transacao` (
  `idtransacao` INT NOT NULL ,
  `idexternotransacao` VARCHAR(45) NULL ,
  `valortransacao` VARCHAR(45) NULL ,
  `codigostatus` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`idtransacao`, `codigostatus`) ,
  INDEX `codigostatusfkidx` (`codigostatus` ASC) ,
  CONSTRAINT `codigostatusfk`
    FOREIGN KEY (`codigostatus` )
    REFERENCES `mydb`.`status` (`codigostatus` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`carrinho`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`carrinho` (
  `idsessao` INT NOT NULL ,
  `skuproduto` INT NOT NULL ,
  `quantidadecarrinho` VARCHAR(45) NULL ,
  PRIMARY KEY (`idsessao`, `skuproduto`) ,
  INDEX `skuprodutofkidx` (`skuproduto` ASC) ,
  INDEX `idsessaofkidx` (`idsessao` ASC) ,
  CONSTRAINT `idsessaofk`
    FOREIGN KEY (`idsessao` )
    REFERENCES `mydb`.`sessao` (`idsessao` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `skuprodutofk`
    FOREIGN KEY (`skuproduto` )
    REFERENCES `mydb`.`produto` (`skuproduto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `mydb` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
