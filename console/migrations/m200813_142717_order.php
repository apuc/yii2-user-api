<?php

use console\models\Seeder;
use yii\db\Migration;

/**
 * Class m200813_142717_order
 */
class m200813_142717_order extends Migration
{
    private $table = 'order';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema($this->table, true) === null) {
            $this->createTable($this->table, [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'sum' => $this->decimal(19, 4),
                'interest_rate' => $this->decimal(6, 3),
                'user_agent' => $this->text(),
                'ip' => $this->string()
            ]);
        }

        $this->addForeignKey(
            'fk_user_id',
            $this->table,
            'user_id',
            'user',
            'id',
            'NO ACTION'
        );

        $seeder = new Seeder();
        $seeder->seedOrder();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_user_id',
            $this->table
        );

        $this->dropTable($this->table);
    }
}
