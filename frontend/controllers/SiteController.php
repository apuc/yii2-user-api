<?php

namespace frontend\controllers;

use frontend\models\User;
use Yii;
use yii\web\Controller;

/**
 * Class SiteController
 * @package frontend\controllers
 */
class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->render('index');
        }
        return $this->render('index', ['model' => User::findOne(Yii::$app->user->id)]);
    }
}
