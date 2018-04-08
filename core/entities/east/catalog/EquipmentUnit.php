<?php

namespace core\entities\east\catalog;

use Yii;

/**
 * This is the model class for table "east_equipment_unit".
 *
 * @property int $equipment_id
 * @property int $unit_id
 *
 * @property Equipment $equipment
 * @property Unit $unit
 */
class EquipmentUnit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'east_equipment_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['equipment_id', 'unit_id'], 'required'],
            [['equipment_id', 'unit_id'], 'integer'],
            [['equipment_id', 'unit_id'], 'unique', 'targetAttribute' => ['equipment_id', 'unit_id']],
            [['equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Equipment::className(), 'targetAttribute' => ['equipment_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
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
        ];
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
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }
}
