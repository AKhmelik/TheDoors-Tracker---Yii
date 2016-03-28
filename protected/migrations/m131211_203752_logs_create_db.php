<?php

class m131211_203752_logs_create_db extends CDbMigration
{
    public function up()
    {
        $this->createTable('geo_log', array(
            'id' => 'pk',
            'longitude' => 'VARCHAR (32)',
            'latitude' => 'VARCHAR (32)',
            'user_id' => 'VARCHAR (255)',
            'time' => 'INT (10)',
        ));
    }

    public function down()
    {
        $this->dropTable('geo_log');
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