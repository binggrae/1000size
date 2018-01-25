<?php


namespace core\forms\power;


use yii\base\Model;
use yii\web\UploadedFile;

class LoadForm extends Model
{

    /** @var UploadedFile */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'extensions' => 'txt']
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'Файл для импорта'
        ];
    }


}