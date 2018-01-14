<?php

use yii\db\Migration;

/**
 * Class m180114_091531_logs
 */
class m180114_091531_logs extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('logs', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'type' => $this->string(),
            'date_start' => $this->integer(),
            'date_end' => $this->integer(),
            'status' => $this->integer(),
            'count' => $this->integer(),
            'link' => $this->text(),
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180114_091531_logs cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180114_091531_logs cannot be reverted.\n";

        return false;
    }
    */
}
