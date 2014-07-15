<?php

class m140714_221600_create_table_saved_queries extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->execute("
           CREATE TABLE saved_queries ( id int NOT NULL AUTO_INCREMENT, FK_userid int NOT NULL,
            query_tag varchar(25) NOT NULL, query text NOT NULL, location varchar(25) DEFAULT '',
            active smallint NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            FOREIGN KEY (FK_userid) REFERENCES user(id) );
        ");
	}

	public function safeDown()
	{
        $this->execute("
          DROP TABLE saved_queries;
        ");
	}

}