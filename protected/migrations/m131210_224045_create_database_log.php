<?php

class m131210_224045_create_database_log extends CDbMigration
{
    public function up()
    {
        $this->createTable('geo_locals', array(
            'id' => 'pk',
            'longitude' => 'VARCHAR (32) NOT NULL',
            'latitude' => 'VARCHAR (32) NOT NULL',
            'user_id' => 'VARCHAR (255)  NOT NULL',
            'time' => 'INT (10) NOT NULL',
        ));
    }

    public function down()
    {
        $this->dropTable('geo_locals');
    }
}