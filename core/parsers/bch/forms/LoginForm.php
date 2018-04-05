<?php


namespace core\parsers\bch\forms;


use core\parsers\bch\Api;
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
            'loginDb' => $this->login,
            'passDB' => $this->password,
        ];
    }

    public function getAction()
    {
        return Api::URL . 'authorization.php';
    }

}