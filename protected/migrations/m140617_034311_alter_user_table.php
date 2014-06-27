<?php

class m140617_034311_alter_user_table extends CDbMigration
{
    /*public function up()
	{
	}

	public function down()
	{
		echo "m140617_034311_alter_user_table does not support migration down.\n";
		return false;
	}*/


	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->execute("ALTER TABLE user ADD COLUMN job_notification tinyint(1) NOT NULL DEFAULT 1");
	}

	public function safeDown()
	{
        $this->dropColumn('user', 'job_notification');
    }
}