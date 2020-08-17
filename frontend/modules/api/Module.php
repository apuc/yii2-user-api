<?php

namespace frontend\modules\api;


use frontend\modules\api\models\User;

/**
 * Class Module
 * @package frontend\modules\api
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }


    public function behaviors()
    {
        return [
            'basicAuth' => [
                'class' => \yii\filters\auth\HttpBasicAuth::className(),
                'auth' => function ($username, $password) {
                    $user = User::find()->where(['email' => $username])->one();
                    if ($user->verifyPassword($password)) {
                        return $user;
                    }
                    return null;
                },
            ],
        ];
    }
}