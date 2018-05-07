<?php

use yii\db\Migration;

/**
 * Class m180505_071217_factors_init
 */
class m180505_071217_factors_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->batchInsert('factors', ['key', 'value'], [
           ['1000size', 1.3],
           ['pg', 1.3],
           ['auto', 1.3],
           ['techno', 1.3],
           ['eastmarine', 1.3],
           ['bch5', 1.3],
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180505_071217_factors_init cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180505_071217_factors_init cannot be reverted.\n";

        return false;
    }
    */
}
