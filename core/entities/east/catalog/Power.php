<?php

namespace core\entities\east\catalog;

use core\parsers\east_catalog\Parser;
use Yii;

/**
 * This is the model class for table "east_power".
 *
 * @property int $id
 * @property string $name
 *
 * @property Equipment[] $equipments
 */
class Power extends \yii\db\ActiveRecord
{
    public $uid;

    public static function get($uid, $name)
    {
        $power = self::find()->where(['name' => $name])->one();
        if (!$power) {
            $power = new self();
            $power->name = $name;
        }
        $power->uid = $uid;

        return $power;
    }

    public function getUrl()
    {
        return Parser::URL . 'catalog/' . $this->uid;
    }

    public static function tableName()
    {
        return 'east_power';
    }


    public function rules()
    {
        return [
            [['name'], 'required'],
            [[ 'name'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipments()
    {
        return $this->hasMany(Equipment::className(), ['power_id' => 'id']);
    }
}
