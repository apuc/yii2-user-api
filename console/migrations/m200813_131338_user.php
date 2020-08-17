<?php

use console\models\Seeder;
use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200813_131338_user
 */
class m200813_131338_user extends Migration
{
    private $table = 'user';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema($this->table, true) === null) {
            $this->createTable($this->table, [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'surname' => $this->string()->notNull(),
                'fathername' => $this->string()->notNull(),
                'birth_date' => $this->timestamp()->notNull(),
                'passport' => $this->string(10)->unique()->notNull(),
                'email' => $this->string()->unique()->notNull(),
                'phone' => $this->string(30)->notNull(),
                'password' => $this->string(),
                'token' => $this->string(),
            ]);

        }
        $seeder = new Seeder();
        $seeder->seedUser();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
