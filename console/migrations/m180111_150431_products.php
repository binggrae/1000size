<?php

use yii\db\Migration;

/**
 * Class m180111_150431_products
 */
class m180111_150431_products extends Migration
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

        $this->createTable('products', [
            'id' => $this->primaryKey(),
            'barcode' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'unit' => $this->string(),
            'storageM' => $this->integer()->notNull()->defaultValue(0),
            'storageV' => $this->integer()->notNull()->defaultValue(0),
            'purchase' => $this->integer()->notNull()->defaultValue(0),
            'retail' => $this->integer()->notNull()->defaultValue(0),
            'brand' => $this->string(),
            'country' => $this->string(),
        ], $tableOptions);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180111_150431_products cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180111_150431_products cannot be reverted.\n";

        return false;
    }
    */
}
