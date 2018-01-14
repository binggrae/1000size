<?php


namespace core\entities\logs;


class CategoryLog extends Log
{
    const TYPE = 'category';

    const CODE_FAILED_LOAD = 1;


    public static function getErrorMessage($code)
    {
        $messages = [
            self::CODE_FAILED_LOAD => 'Failed load',
        ];
        return $messages[$code];
    }


}