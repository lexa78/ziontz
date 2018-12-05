<?php

use yii\db\Migration;

/**
 * Handles the creation of table `logs`.
 */
class m181204_153638_create_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('logs', [
            'id' => $this->primaryKey(),
            'log_date' => $this->dateTime()->notNull(),
            'url_from' => $this->string(50)->notNull(),
            'url_to' => $this->string(50)->notNull(),
            'ip_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-logs-ip_id',
            'logs',
            'ip_id',
            'ips',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('logs');
    }
}
