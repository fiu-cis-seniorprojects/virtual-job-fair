<?php

class m140622_223610_alter_user_table_add_column_looking_for_job extends CDbMigration
{
	/*public function up()
	{
	}

	public function down()
	{
		echo "m140622_223610_alter_user_table_add_column_looking_for_job does not support migration down.\n";
		return false;
	}*/


	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->execute('ALTER TABLE user ADD COLUMN looking_for_job tinyint(1) NOT NULL DEFAULT 1');
	}

	public function safeDown()
	{
        $this->dropColumn('user', 'looking_for_job');
	}
}