<?php

class m140319_230317_add_option_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('geo_options', array(
            'data'=>'varchar (64) NOT NULL',
            'parameter'=> 'varchar (64 )',
        ));
        $this->createIndex('data', 'geo_options', 'data', true);
        $this->insert('geo_options', array('data'=>'metric_core', 'parameter'=>''));
	}

	public function down()
	{
        $this->dropIndex('data', 'geo_options');
		$this->dropTable('geo_options');
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