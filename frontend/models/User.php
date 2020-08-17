<?php

namespace frontend\models;

use DateTime;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $fathername
 * @property string|null $birth_date
 * @property string|null $passport
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $password
 * @property string|null $token
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    CONST MIN_AGE_LIMIT = 18;
    CONST MAX_AGE_LIMIT = 60;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'fathername', 'email', 'birth_date', 'passport', 'phone'], 'required'],
            [['birth_date'], 'date', 'format' => 'd-M-y'],
            ['birth_date', 'validateAge'],
            [['name', 'surname', 'fathername', 'email'], 'string', 'max' => 255],
            [['passport'], 'string', 'max' => 10],
            ['passport', 'validatePassport'],
            [['phone'], 'string', 'max' => 16],
            ['phone', 'validatePhone'],
            ['email', 'email'],
            [['email', 'passport'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'fathername' => 'Отчество',
            'birth_date' => 'Дата рождения',
            'passport' => 'Серия и номер паспорта',
            'email' => 'Email',
            'phone' => 'Номер телефона',
        ];
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function _save(): string
    {
        $this->generateToken();
        $password = $this->generatePassword();
        $this->save();
        return $password;
    }

    /**
     * @throws \yii\base\Exception
     */
    public function generateToken()
    {
        $this->token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function generatePassword(): string
    {
        $password = Yii::$app->security->generateRandomString(8);
        $this->password = Yii::$app->security->generatePasswordHash($password);
        return $password;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $order = new Order();
        $order->saveOrder($this);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->birth_date = date("y-m-d", strtotime($this->birth_date));

            return true;
        }
        return false;
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateRus($attribute, $params, $validator)
    {
        $isOk = preg_match('@[А-Яа-я]{2,255}@u', $this->$attribute);
        if (!$isOk) {
            $this->addError($attribute, "При вводе '$attribute' может участвовать только кирилица");
        }
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @throws \Exception
     */
    public function validateAge($attribute, $params, $validator)
    {
        $cur_date = new DateTime();
        $input_time = new DateTime($this->$attribute);
        $diff_year = date_diff($cur_date, $input_time)->y;
        if ($diff_year < self::MIN_AGE_LIMIT) {
            $this->addError($attribute, 'Возраст не может быть менее ' . self::MIN_AGE_LIMIT . ' лет');
        } else if ($diff_year > self::MAX_AGE_LIMIT) {
            $this->addError($attribute, 'Возраст не может быть более ' . self::MAX_AGE_LIMIT . ' лет');
        }
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validatePassport($attribute, $params, $validator)
    {
        $this->$attribute = preg_replace('@[^0-9]@u', '', $this->$attribute);
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validatePhone($attribute, $params, $validator)
    {
        $isOk = preg_match('@\+7[0-9\-()]{10}@u', $this->$attribute);
        if (!$isOk) {
            $this->addError($attribute, 'Неверный формат номера телефона');
        }
    }

    /**
     * @param $email
     * @param $password
     */
    public function sendPassword($email, $password)
    {
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($email)
            ->setSubject('Регситрация')
            ->setHtmlBody("Вы успешно зарегистрированы!<br/>Ваш пароль $password")
            ->send();
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return $this->token;
    }

    /**
     * @param $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
}
