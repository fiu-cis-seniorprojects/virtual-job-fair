<?php

class m140707_183328_alter_user_table_job_interest extends CDbMigration
{
	public function safeUp()
	{
        $this->execute("
           ALTER TABLE `jobfairdb`.`user`
                        ADD COLUMN `job_int_date` TINYINT(1) NOT NULL DEFAULT 0
        ");
	}

	public function safeDown()
	{
        $this->execute("
                        ALTER TABLE `jobfairdb`.`user`
                        DROP COLUMN `job_int_date`
        ");
	}

}