<?php

use yii\db\Migration;

/**
 * Class m180112_151817_task
 */
class m180112_151817_task extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'pid' => $this->integer(),
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180112_151817_task cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180112_151817_task cannot be reverted.\n";

        return false;
    }
    */
}
