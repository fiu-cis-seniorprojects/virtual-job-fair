<?php

class m140607_063219_create_match_notification_table extends CDbMigration
{
	/*
    public function up()
	{
	}

	public function down()
	{
		echo "m140607_063219_create_match_notification_table does not support migration down.\n";
		return false;
	}*/


	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->createTable('match_notification', array(
            'userid'=>'int(11) NOT NULL',
            'status'=>'tinyint(1) NOT NULL DEFAULT 0',
            'date_modified'=>'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP'
        ));
	}

	public function safeDown()
	{
        $this->dropForeignKey('fk_global_match_user', 'match_notification');
	}
}