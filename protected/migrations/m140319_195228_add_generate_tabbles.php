<?php

class m140319_195228_add_generate_tabbles extends CDbMigration
{
    public function up()
    {
        $this->createTable('geo_form', array(
                'id' => 'pk',
                'type' => 'VARCHAR (32) NOT NULL',
            ), 'ENGINE = InnoDB COLLATE utf8_general_ci'
        );

        $this->createTable('geo_form_inputs', array(
                'id' => 'pk',
                'form_id' => 'INT(11) NOT NULL',
                'form_value' => 'VARCHAR(100)',
                'static_pos' => 'TINYINT DEFAULT NULL'
            ), 'ENGINE = InnoDB COLLATE utf8_general_ci'
        );

        $this->addForeignKey('fk_input_form','geo_form_inputs','form_id','geo_form','id','cascade','cascade');

        $this->createTable('geo_form_cell', array(
            'id' => 'pk',
            'form_id' => 'INT(11) NOT NULL',
            'cell_value' => 'VARCHAR(255) NOT NULL',
            'checked' => 'TINYINT(1) DEFAULT 0'
        ), 'ENGINE = InnoDB COLLATE utf8_general_ci'
        );

        $this->addForeignKey('fk_input_cell','geo_form_cell','form_id','geo_form','id','cascade','cascade');

    }

    public function down()
    {
        $this->dropForeignKey('fk_input_form','geo_form_inputs');
        $this->dropForeignKey('fk_input_cell','geo_form_cell');
        $this->dropTable('geo_form');
        $this->dropTable('geo_form_inputs');
        $this->dropTable('geo_form_cell');
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