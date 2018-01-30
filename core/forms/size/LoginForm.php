<?php


namespace core\forms\size;


use yii\base\Model;

/**
 * Class LoginForm
 * @package core\forms\size
 * @property array $postData
 */
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
            'return_to' => 'https://opt.1000size.ru/'
        ];
    }
}