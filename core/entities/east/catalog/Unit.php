<?php

namespace core\entities\east\catalog;

use core\parsers\east_catalog\Parser;
use Yii;

/**
 * This is the model class for table "east_unit".
 *
 * @property int $id
 * @property string $name
 *
 * @property EquipmentUnit[] $equipmentUnits
 * @property Equipment[] $equipment
 * @property EquipmentUnitPart[] $equipmentUnitParts
 */
class Unit extends \yii\db\ActiveRecord
{

    /** @var Equipment */
    public $eq;

    public $uid;

    public static function get($uid, $name)
    {
        $unit = self::find()->where(['name' => $name])->one();
        if (!$unit) {
            $unit = new self();
            $unit->name = $name;
            $unit->save();
        }
        $unit->uid = $uid;

        return $unit;
    }

    public function attachTo(Equipment $equipment)
    {
        $relation = EquipmentUnit::find()->where(['equipment_id' => $equipment->id, 'unit_id' => $this->id])->one();
        if (!$relation) {
            $relation = new EquipmentUnit();
            $relation->equipment_id = $equipment->id;
            $relation->unit_id = $this->id;
            $relation->save();
        }
        $this->eq =  $equipment;
    }

    public function getUrl()
    {
        return Parser::URL . "unit/{$this->eq->uid}/{$this->uid}";
    }

    public static function tableName()
    {
        return 'east_unit';
    }


    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
    public function getEquipmentUnits()
    {
        return $this->hasMany(EquipmentUnit::className(), ['unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipment()
    {
        return $this->hasMany(Equipment::className(), ['id' => 'equipment_id'])->viaTable('east_equipment_unit', ['unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentUnitParts()
    {
        return $this->hasMany(EquipmentUnitPart::className(), ['unit_id' => 'id']);
    }
}
