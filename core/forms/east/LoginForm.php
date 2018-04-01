<?php


namespace core\forms\east;


use yii\base\Model;

/**
 * Class LoginForm
 * @package core\forms\power
 * @property array $postData
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

    public function getPostData()
    {
        return [
            'login' => $this->username,
            'pass' => $this->password,
        ];
    }
}