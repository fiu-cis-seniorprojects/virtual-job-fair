<?php

class m140624_020245_company_name_column extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->execute("
            ALTER TABLE `jobfairdb`.`job`
                        ADD COLUMN `comp_name` VARCHAR(255) NULL DEFAULT NULL,
                        ADD FULLTEXT(type,title,description,comp_name);

        ");
	}

	public function safeDown()
	{
        $this->execute("
                        ALTER TABLE `jobfairdb`.`job`
                        DROP COLUMN `comp_name`,
                        DROP INDEX type;
        ");
	}

}