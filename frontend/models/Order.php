<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $user_id
 * @property float|null $sum
 * @property float|null $interest_rate
 * @property string|null $user_agent
 * @property string|null $ip
 *
 * @property User $user
 */
class Order extends \yii\db\ActiveRecord
{
    CONST sum = '777.77';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['sum', 'interest_rate'], 'number'],
            [['user_agent'], 'string'],
            [['ip'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'sum' => 'Sum',
            'interest_rate' => 'Interest Rate',
            'user_agent' => 'User Agent',
            'ip' => 'Ip',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @param User $user
     */
    public function saveOrder(User $user)
    {
        $this->sum = self::sum;
        $this->ip = Yii::$app->request->userIP;
        $this->user_id = $user->id;
        $this->interest_rate = 1;
        $this->user_agent = Yii::$app->request->userAgent;
        $this->save();
    }
}
