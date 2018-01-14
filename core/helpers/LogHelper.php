<?php


namespace core\helpers;


class LogHelper
{
    const STATUS_JOB = 0;
    const STATUS_ERROR = 5;
    const STATUS_END = 10;


    public static function getClassByCode($code)
    {
        switch ($code) {
            case self::STATUS_JOB :
                return 'info';
                break;
            case self::STATUS_ERROR :
                return 'danger';
                break;
            case self::STATUS_END:
                return 'success';
                break;
            default:
                return 'default';
        }
    }

    public static function getLabelByCode($code)
    {
        switch ($code) {
            case self::STATUS_JOB :
                return 'Job';
                break;
            case self::STATUS_ERROR :
                return 'Error';
                break;
            case self::STATUS_END:
                return 'End';
                break;
            default:
                return $code;
        }
    }

}