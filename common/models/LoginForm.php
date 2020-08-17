<?php

namespace common\models;

use frontend\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['password', 'validateUser'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }


    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateUser($attribute, $params, $validator)
    {
        $user = $this->getUser();
        if (!Yii::$app->security->validatePassword($this->$attribute, $user->password)) {
            $this->addError($attribute, 'Неверный email или пароль');
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }
        return $this->_user;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        $user = $this->getUser();

        if ($user !== null) {
            if ($user->verifyPassword($this->password)) {
                $user->generateToken();
                $user->save();

                return Yii::$app->user->login($this->getUser(), 3600 * 24 * 30);
            } else {
                $this->addError('password', 'Incorrect password');
            }
        } else {
            $this->addError('email', 'Incorrect email');
        }

        return false;
    }
}
