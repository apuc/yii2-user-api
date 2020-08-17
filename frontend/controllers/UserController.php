<?php


namespace frontend\controllers;


use common\models\LoginForm;
use frontend\models\Order;
use frontend\models\User;
use Yii;
use yii\web\Controller;

/**
 * Class UserController
 * @package frontend\controllers
 */
class UserController extends Controller
{
    /**
     * @return string
     */
    public function actionRegister()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post()) AND $model->validate()) {
            $password = $model->_save();
            $model->sendPassword($model->email, $password);
            $this->createOrder($model);
            $this->redirect('/');
        } else {
            return $this->render('register', ['model' => $model]);
        }
    }


    /**
     * @param User $user
     */
    public function createOrder(User $user)
    {
        $order = new Order();
        $order->saveOrder($user);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) AND $model->validate()) {
            $model->login();
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}