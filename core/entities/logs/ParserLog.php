<?php


namespace core\entities\logs;


class ParserLog extends Log
{
    const TYPE = 'parser';

    const CODE_ALREADY_RUNNING = 1;
    const CODE_BAD_LOGIN = 2;
    const CODE_UNKNOWN = 3;


    public static function getErrorMessage($code)
    {
        $messages = [
            self::CODE_ALREADY_RUNNING => 'Parser already running',
            self::CODE_BAD_LOGIN => 'Bad login',
            self::CODE_UNKNOWN => 'unknown error'
        ];
        return $messages[$code];
    }

}