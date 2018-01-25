<?php

use yii\db\Migration;

/**
 * Class m180125_115535_nnull_storage
 */
class m180125_115535_nnull_storage extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('power_products', 'storageV', $this->integer());

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180125_115535_nnull_storage cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180125_115535_nnull_storage cannot be reverted.\n";

        return false;
    }
    */
}
