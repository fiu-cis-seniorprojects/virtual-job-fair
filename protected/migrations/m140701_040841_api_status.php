<?php

class m140701_040841_api_status extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->execute(
            "
                CREATE TABLE `jobfairdb`.`api_status` (
                                                        `date_modified` DATETIME NOT NULL,
                                                        `status` INT NOT NULL DEFAULT 0,
                                                        PRIMARY KEY (`date_modified`));

            "
        );
	}

	public function safeDown()
	{
        $this->execute(
            "
                DROP TABLE `jobfairdb`.`api_status`;
            "
        );
	}
}