<?php

use yii\db\Migration;

/**
 * Class m180505_070840_factors
 */
class m180505_070840_factors extends Migration
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

        $this->createTable('factors', [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull(),
            'value' => $this->float()->notNull(),
        ], $tableOptions);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180505_070840_factors cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180505_070840_factors cannot be reverted.\n";

        return false;
    }
    */
}
