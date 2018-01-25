<?php


namespace core\forms\power;


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

    public $token;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
        ];
    }

    public function getPostData()
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'session_id' => $this->token,
        ];
    }
}