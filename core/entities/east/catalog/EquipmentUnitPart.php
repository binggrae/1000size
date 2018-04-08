<?php

namespace core\entities\east\catalog;

use Yii;

/**
 * This is the model class for table "east_equipment_unit_part".
 *
 * @property int $equipment_id
 * @property int $unit_id
 * @property int $part_id
 * @property int $quantity
 *
 * @property Unit $unit
 * @property Equipment $equipment
 * @property Part $part
 */
class EquipmentUnitPart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'east_equipment_unit_part';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['equipment_id', 'unit_id', 'part_id', 'quantity'], 'required'],
            [['equipment_id', 'unit_id', 'part_id', 'quantity'], 'integer'],
            [['equipment_id', 'unit_id', 'part_id'], 'unique', 'targetAttribute' => ['equipment_id', 'unit_id', 'part_id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
            [['equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Equipment::className(), 'targetAttribute' => ['equipment_id' => 'id']],
            [['part_id'], 'exist', 'skipOnError' => true, 'targetClass' => Part::className(), 'targetAttribute' => ['part_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'equipment_id' => 'Equipment ID',
            'unit_id' => 'Unit ID',
            'part_id' => 'Part ID',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipment()
    {
        return $this->hasOne(Equipment::className(), ['id' => 'equipment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPart()
    {
        return $this->hasOne(Part::className(), ['id' => 'part_id']);
    }
}
