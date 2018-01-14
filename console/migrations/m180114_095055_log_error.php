<?php

use yii\db\Migration;

/**
 * Class m180114_095055_log_error
 */
class m180114_095055_log_error extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('logs', 'error_data', $this->text());

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180114_095055_log_error cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180114_095055_log_error cannot be reverted.\n";

        return false;
    }
    */
}
