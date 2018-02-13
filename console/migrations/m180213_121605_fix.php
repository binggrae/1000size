<?php

use yii\db\Migration;

/**
 * Class m180213_121605_fix
 */
class m180213_121605_fix extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('products', 'category_id', $this->integer()->after('id'));
        $this->addColumn('products', 'link', $this->string()->after('category_id'));
        $this->addColumn('products', 'status', $this->integer()->after('link'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180213_121605_fix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180213_121605_fix cannot be reverted.\n";

        return false;
    }
    */
}
