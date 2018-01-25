<?php

use yii\db\Migration;

/**
 * Class m180125_131257_up
 */
class m180125_131257_up extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('power_products', 'purchase', $this->float(2)->notNull()->defaultValue(0));
        $this->alterColumn('power_products', 'retail', $this->float(2)->notNull()->defaultValue(0));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180125_131257_up cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180125_131257_up cannot be reverted.\n";

        return false;
    }
    */
}
