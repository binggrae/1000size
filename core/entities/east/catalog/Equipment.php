<?php

namespace core\entities\east\catalog;

use core\parsers\east_catalog\Parser;
use Yii;

/**
 * This is the model class for table "east_equipment".
 *
 * @property int $id
 * @property int $power_id
 * @property string $uid
 * @property string $name
 * @property string $year
 * @property string $description
 *
 * @property Power $power
 * @property EquipmentUnit[] $equipmentUnits
 * @property Unit[] $units
 * @property EquipmentUnitPart[] $equipmentUnitParts
 */
class Equipment extends \yii\db\ActiveRecord
{

    public static function get($uid, $name, $year, $description)
    {
        $equipment = self::find()->where(['uid' => $uid, 'name' => $name])->one();
        if (!$equipment) {
            $equipment = new self();
            $equipment->uid = $uid;
            $equipment->name = $name;
            $equipment->year = $year;
            $equipment->description = $description;
        }

        return $equipment;
    }

    public function getUrl()
    {
        return Parser::URL . 'equipment/' . $this->uid;
    }


    public static function tableName()
    {
        return 'east_equipment';
    }


    public function rules()
    {
        return [
            [['power_id', 'uid', 'name', 'year'], 'required'],
            [['power_id'], 'integer'],
            [['uid', 'name', 'year'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['power_id'], 'exist', 'skipOnError' => true, 'targetClass' => Power::className(), 'targetAttribute' => ['power_id' => 'id']],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'power_id' => 'Power ID',
            'uid' => 'Link',
            'name' => 'Name',
            'year' => 'Year',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPower()
    {
        return $this->hasOne(Power::className(), ['id' => 'power_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentUnits()
    {
        return $this->hasMany(EquipmentUnit::className(), ['equipment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnits()
    {
        return $this->hasMany(Unit::className(), ['id' => 'unit_id'])->viaTable('east_equipment_unit', ['equipment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentUnitParts()
    {
        return $this->hasMany(EquipmentUnitPart::className(), ['equipment_id' => 'id']);
    }
}
