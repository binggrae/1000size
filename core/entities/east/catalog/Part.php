<?php

namespace core\entities\east\catalog;

use Yii;

/**
 * This is the model class for table "east_part".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 *
 * @property EquipmentUnitPart[] $equipmentUnitParts
 */
class Part extends \yii\db\ActiveRecord
{

    public $quantity;

    public $unit;

    public static function get($uid, $name, $quantity)
    {
        $part = self::find()->where(['uid' => $uid])->one();
        if(!$part) {
            $part = new self();
            $part->uid = $uid;
            $part->name = $name;
            $part->save();
        }

        $part->quantity = $quantity;

        return $part;
    }

    public function attachTo(Unit $unit)
    {
        $relation = EquipmentUnitPart::find()->where(['equipment_id' => $unit->eq->id, 'unit_id' => $this->id, 'part_id' =>$this->id])->one();
        if (!$relation) {
            $relation = new EquipmentUnitPart();
            $relation->equipment_id = $unit->eq->id;
            $relation->unit_id = $unit->id;
            $relation->part_id = $this->id;
            $relation->quantity = $this->quantity;
            $relation->save();
        }
    }

    public static function tableName()
    {
        return 'east_part';
    }

    
    public function rules()
    {
        return [
            [['uid', 'name'], 'required'],
            [['uid', 'name'], 'string', 'max' => 255],
        ];
    }

    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentUnitParts()
    {
        return $this->hasMany(EquipmentUnitPart::className(), ['part_id' => 'id']);
    }
}
