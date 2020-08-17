<?php


namespace frontend\modules\api\controllers;


use yii\rest\ActiveController;

/**
 * Class UserController
 * @package frontend\controllers
 */
class UserController extends ActiveController
{
    public $modelClass = 'frontend\modules\api\models\User';
}