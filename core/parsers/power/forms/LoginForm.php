<?php


namespace core\parsers\power\forms;


use core\parsers\power\Parser;
use yii\base\Model;

/**
 * @property string $action
 * @property array $data
 */
class LoginForm extends Model
{
    public $login;

    public $password;

    public function getData()
    {
        return [
            'USER_LOGIN' => $this->login,
            'USER_PASSWORD' => $this->password,
            'USER_REMEMBER' => 'Y',
            'backurl' => '/profile.html',
            'AUTH_FORM' => 'Y',
            'TYPE' => 'auth'
        ];
    }

    public function getAction()
    {
        return Parser::URL . 'auth_check_user';
    }

}