<?php

class m131211_194346_localisation_db_create extends CDbMigration
{
	public function up()
	{
        $this->createTable('geo_unique', array(
            'id' => 'pk',
            'longitude' => 'VARCHAR (32) NOT NULL',
            'latitude' => 'VARCHAR (32) NOT NULL',
            'user_id' => 'VARCHAR (255)  NOT NULL',
            'time' => 'INT (10) NOT NULL',
        ));
	}

	public function down()
	{
        $this->dropTable('geo_unique');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}