<?php

use yii\db\Migration;

class m250615_055958_test_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%test_table}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%test_table}}');
    }
}
