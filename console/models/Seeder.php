<?php


namespace console\models;


use Yii;

/**
 * Class Seeder
 * @package console\models
 */
class Seeder
{
    CONST DEFAULT_COUNT_ROW = 20;

    /**
     * @param int|null $count_row
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function seedUser(int $count_row = null)
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();

        $seeder = new \tebazil\yii2seeder\Seeder();
        $generator = $seeder->getGeneratorConfigurator();
        $faker = $generator->getFakerConfigurator();

        $count = $count_row === null ? self::DEFAULT_COUNT_ROW : $count_row;

        $seeder->table('user')->columns([
            'id', //automatic pk
            'name' => $faker->firstName,
            'surname' => $faker->text(20),
            'fathername' => $faker->text(20),
            'birth_date' => $faker->date('y-m-d'),
            'passport' => $faker->password(10, 10),
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,
            'password' => Yii::$app->security->generateRandomString(8),
            'token' => Yii::$app->security->generateRandomString(32),
        ])->rowQuantity($count);

        $seeder->refill();
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
    }

    /**
     * @param int|null $count_row
     * @throws \yii\db\Exception
     */
    public function seedOrder(int $count_row = null)
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();

        $seeder = new \tebazil\yii2seeder\Seeder();
        $generator = $seeder->getGeneratorConfigurator();
        $faker = $generator->getFakerConfigurator();

        $count = $count_row === null ? self::DEFAULT_COUNT_ROW : $count_row;
        $seeder->table('order')->columns([
            'id',
            'user_id' => $generator->pk,
            'sum' => function () {
                return (float)(mt_rand(0, 10000) / mt_rand(1, 100));
            },
            'interest_rate' => function () {
                return (float)(mt_rand(0, 99) + mt_rand(0, 100) / 100);
            },
            'user_agent' => $faker->text(50),
            'ip' => $faker->text(15),
        ])->rowQuantity($count);

        $seeder->refill();
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
    }
}