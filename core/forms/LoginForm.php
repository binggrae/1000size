<?php


namespace core\forms;


use yii\base\Model;

class LoginForm extends Model
{

    public $login;
    public $password;

    public $token;

    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
        ];
    }

    public function getPostData()
    {
        return [
            'login[mail]' => $this->login,
            'login[password]' => $this->password,
            'login[_csrf_token]' => $this->token,
        ];
    }
}