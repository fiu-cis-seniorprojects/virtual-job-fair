<?php

/*
 * All schema changes related to the cis careerpath integration + API
 */

class m140610_182901_careerpath_sync extends CDbMigration
{
	public function safeUp()
	{
        $this->execute("
                        CREATE TABLE `jobfairdb`.`api_auth` (
                                    `id` INT NOT NULL AUTO_INCREMENT,
                                    `valid_key` VARCHAR(255) NOT NULL,
                                    PRIMARY KEY (`id`),
                                    UNIQUE INDEX `key_UNIQUE` (`valid_key` ASC));


                        ALTER TABLE `jobfairdb`.`user`
                        CHANGE COLUMN `first_name` `first_name` VARCHAR(45) NULL ,
                        CHANGE COLUMN `registration_date` `registration_date` DATETIME NULL ,
                        CHANGE COLUMN `activation_string` `activation_string` VARCHAR(45) NULL ,
                        CHANGE COLUMN `last_name` `last_name` VARCHAR(45) NULL ;

                        ALTER TABLE `jobfairdb`.`job`
                        ADD COLUMN `posting_url` TEXT NULL DEFAULT NULL AFTER `matches_found`;

        ");


	}

	public function safeDown()
	{
        $this->execute("
                        DROP TABLE IF EXISTS `jobfairdb`.`api_auth`;

                        ALTER TABLE `jobfairdb`.`user`
                        CHANGE COLUMN `first_name` `first_name` VARCHAR(45) NOT NULL ,
                        CHANGE COLUMN `registration_date` `registration_date` DATETIME NOT NULL ,
                        CHANGE COLUMN `activation_string` `activation_string` VARCHAR(45) NOT NULL ,
                        CHANGE COLUMN `last_name` `last_name` VARCHAR(45) NOT NULL ;

                        ALTER TABLE `jobfairdb`.`job`
                        DROP COLUMN `posting_url`;
        ");
	}
}