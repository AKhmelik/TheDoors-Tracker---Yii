<?php

class m140326_201156_add_table_game_points extends CDbMigration
{
	public function up()
	{
        $this->createTable('geo_points', array(
            'id' => 'pk',
            'cores' => 'VARCHAR (32)',
            'street' => 'VARCHAR (255)',
            'house' => 'VARCHAR (255)',
            'comments' => 'VARCHAR (255)',
        ));
	}

	public function down()
	{
        $this->dropTable('geo_points');
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