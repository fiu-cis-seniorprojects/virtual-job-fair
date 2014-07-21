<?php

class m140721_070311_api_auth_description extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->execute("

                ALTER TABLE `jobfairdb`.`api_auth` ADD COLUMN `description` TEXT NOT NULL  AFTER `valid_key` ;

        ");
	}

	public function safeDown()
	{
        $this->execute("

                ALTER TABLE `jobfairdb`.`api_auth` DROP COLUMN `description` ;

        ");
	}
}