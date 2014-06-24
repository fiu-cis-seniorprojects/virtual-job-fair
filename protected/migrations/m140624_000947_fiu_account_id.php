<?php

class m140624_000947_fiu_account_id extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->execute("
                        ALTER TABLE `jobfairdb`.`user`
                        ADD COLUMN `fiu_account_id` VARCHAR(45) NULL DEFAULT NULL AFTER `job_notification`;
        ");

    }

	public function safeDown()
	{
        $this->execute("
                        ALTER TABLE `jobfairdb`.`user`
                        DROP COLUMN `fiu_account_id`;
        ");

    }

}