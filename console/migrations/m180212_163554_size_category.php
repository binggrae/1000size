<?php

use yii\db\Migration;

/**
 * Class m180212_163554_size_category
 */
class m180212_163554_size_category extends Migration
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
        $this->createTable('size_categories', [
            'id' => $this->primaryKey(),
            'path' => $this->string(),
            'link' => $this->string(),
            'title' => $this->string(),
            'is_child' => $this->boolean(),
            'status' => $this->integer(),
            'count' => $this->integer(),
        ], $tableOptions);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('size_categories');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180212_163554_size_category cannot be reverted.\n";

        return false;
    }
    */
}
