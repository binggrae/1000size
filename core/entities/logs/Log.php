<?php

namespace core\entities\logs;

use core\helpers\LogHelper;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Json;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $type
 * @property int $date_start
 * @property int $date_end
 * @property int $status
 * @property int $count
 * @property string $link
 * @property string $error_data
 */
class Log extends \yii\db\ActiveRecord
{
    const TYPE = null;


    public static function start($parent_id = null, $link = null)
    {
        $log = new static();
        $log->type = static::TYPE;
        $log->date_start = time();
        $log->status = LogHelper::STATUS_JOB;
        $log->parent_id = $parent_id;
        $log->link = $link;

        $log->save();
        return $log;
    }


    public function end($count)
    {
        $this->status = LogHelper::STATUS_END;
        $this->date_end = time();
        $this->count = $count;

        $this->save();
        return $this;
    }

    public function error($code, $message = null)
    {
        $this->date_end = time();
        $this->status = LogHelper::STATUS_ERROR;
        $this->error_data = Json::encode([
            'code' => $code,
            'message' => $message ? $message : static::getErrorMessage($code)
        ]);


        $this->save();
        return $this;
    }

    public static function getErrorMessage($code)
    {
        return '';
    }

    public function init()
    {
        $this->type = static::TYPE;
        parent::init();
    }

    public static function find()
    {
        return new LogQuery(get_called_class(), ['type' => static::TYPE]);
    }

    public static function instantiate($row)
    {
        switch ($row['type']) {
            case CategoryLog::TYPE:
                return new CategoryLog();
            case ParserLog::TYPE:
                return new ParserLog();
            case XlsLog::TYPE:
                return new XlsLog();
            case XmlLog::TYPE:
                return new XmlLog();
            default:
                return new self;
        }
    }

    public static function tableName()
    {
        return 'logs';
    }


    public function rules()
    {
        return [
            [['date_start', 'date_end', 'status', 'count', 'parent_id'], 'integer'],
            [['link', 'error_data'], 'string'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'status' => 'Status',
            'count' => 'Count',
            'link' => 'Link',
            'error_data' => 'Error',
        ];
    }
}


class LogQuery extends ActiveQuery
{
    public $type;

    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['type' => $this->type]);
        }
        return parent::prepare($builder);
    }
}
