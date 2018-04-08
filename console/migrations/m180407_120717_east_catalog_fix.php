<?php

use yii\db\Migration;

/**
 * Class m180407_120717_east_catalog_fix
 */
class m180407_120717_east_catalog_fix extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addForeignKey('fk_equipmentEquipment_power',
            'east_equipment', 'power_id',
            'east_power', 'id',
            'CASCADE', 'RESTRICT'
        );

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180407_120717_east_catalog_fix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180407_120717_east_catalog_fix cannot be reverted.\n";

        return false;
    }
    */
}
