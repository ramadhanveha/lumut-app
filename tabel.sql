CREATE TABLE IF NOT EXISTS `account` (
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(250) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `role` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`username`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `post` (
  `idpost` INT NOT NULL AUTO_INCREMENT,
  `title` TEXT NOT NULL,
  `content` TEXT NOT NULL,
  `date` DATETIME NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idpost`),
  INDEX `fk_post_account_idx` (`username` ASC),
  CONSTRAINT `fk_post_account`
    FOREIGN KEY (`username`)
    REFERENCES `account` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;