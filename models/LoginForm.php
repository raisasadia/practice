<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/*
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $name;
    public $password;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['name', 'password'], 'required'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'name' => 'Your Username',
            'password' => 'Your Password',
            'rememberMe' => 'Keep me logged in',

        ];
    }


    public function login()
    {
        $user = User::findOne(['name' => $this->name]);
        if ($user && Yii::$app->security->validatePassword($this->password, $user->password)) {
            return Yii::$app->user->login($user);
        }

        $this->addError('password', 'Incorrect username or password.');
        return false;
    }

}
