<?php


namespace core\parsers\east\forms;


use core\parsers\east\Parser;
use yii\base\Model;

/**
 * Class LoginForm
 * @package core\forms\power
 * @property string $action
 * @property array $data
 */
class LoginForm extends Model
{

    public $username;
    public $password;


    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
        ];
    }

    public function getData()
    {
        return [
            'login' => $this->username,
            'pass' => $this->password,
        ];
    }

    public function getAction()
    {
        return Parser::URL . 'login';
    }
}