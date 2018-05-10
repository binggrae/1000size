<?php

use yii\db\Migration;

/**
 * Class m180510_060842_size
 */
class m180510_060842_size extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('products', 'category_id');
        $this->addColumn('products', 'load_ts', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180510_060842_size cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180510_060842_size cannot be reverted.\n";

        return false;
    }
    */
}
