<?php

namespace core\entities\size;

/**
 * This is the model class for table "size_categories".
 *
 * @property int $id
 * @property string $path
 * @property string $link
 * @property string $title
 * @property int $is_child
 * @property string $status
 */
class Categories extends \yii\db\ActiveRecord
{
    private $all;

    private $loaded;

    public static function create($path, $link, $title)
    {
        $model = new self([
            'path' => $path,
            'link' => $link,
            'title' => $title,
        ]);
        $model->is_child = true;
        $model->status = 0;

        return $model;
    }

    public static function tableName()
    {
        return 'size_categories';
    }


    public function rules()
    {
        return [
            [['is_child'], 'boolean'],
            [['status'], 'integer'],
            [['path', 'link', 'title'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'link' => 'Link',
            'title' => 'Title',
            'is_child' => 'Is Child',
            'status' => 'Status',
        ];
    }

    public function getAllProductsCount()
    {
        if(!$this->all) {
            $this->all = Products::find()
                ->where(['category_id' => $this->id])
                ->count();
        }
        return $this->all;
    }

    public function getLoadedProductsCount()
    {
        if(!$this->loaded) {
            $this->loaded = Products::find()
                ->where(['category_id' => $this->id])
                ->andWhere(['status' => 0])
                ->count();
        }
        return $this->loaded;
    }
}
