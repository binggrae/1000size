<?php

use yii\db\Migration;

/**
 * Class m180407_113615_east_catalog
 */
class m180407_113615_east_catalog extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Мощность
        $this->createTable('east_power', [
            'id' => $this->primaryKey(),
            'uid' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        // Модель двигателя
        $this->createTable('east_equipment', [
            'id' => $this->primaryKey(),
            'power_id' => $this->integer()->notNull(),
            'uid' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'year' => $this->string()->notNull(),
            'description' => $this->text(),
        ], $tableOptions);

        // Блок
        $this->createTable('east_unit', [
            'id' => $this->primaryKey(),
            'uid' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        // Запчасть
        $this->createTable('east_part', [
            'id' => $this->primaryKey(),
            'uid' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        // Модель + блок
        $this->createTable('east_equipment_unit', [
            'equipment_id' => $this->integer()->notNull(),
            'unit_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('pk_equipmentUnit', 'east_equipment_unit', [
            'equipment_id',
            'unit_id',
        ]);

        $this->addForeignKey('fk_equipmentUnit_equipment',
            'east_equipment_unit', 'equipment_id',
            'east_equipment', 'id',
            'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey('fk_equipmentUnit_unit',
            'east_equipment_unit', 'unit_id',
            'east_unit', 'id',
            'CASCADE', 'RESTRICT'
        );

        // Модель + блок + Запчасть
        $this->createTable('east_equipment_unit_part', [
            'equipment_id' => $this->integer()->notNull(),
            'unit_id' => $this->integer()->notNull(),
            'part_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('pk_equipmentUnitPart', 'east_equipment_unit_part', [
            'equipment_id',
            'unit_id',
            'part_id',
        ]);

        $this->addForeignKey('fk_equipmentUnitPart_equipment',
            'east_equipment_unit_part', 'equipment_id',
            'east_equipment', 'id',
            'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey('fk_equipmentPart_unit',
            'east_equipment_unit_part', 'unit_id',
            'east_unit', 'id',
            'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey('fk_equipmentUnitPart_part',
            'east_equipment_unit_part', 'part_id',
            'east_part', 'id',
            'CASCADE', 'RESTRICT'
        );


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180407_113615_east_catalog cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180407_113615_east_catalog cannot be reverted.\n";

        return false;
    }
    */
}
