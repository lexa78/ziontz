<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ip_os_browser`.
 */
class m181205_064201_create_ip_os_browser_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ip_os_browser', [
            'id' => $this->primaryKey(),
            'ip_id' => $this->integer()->notNull(),
            'os_id' => $this->integer()->notNull(),
            'browser_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-iob-ip_id',
            'ip_os_browser',
            'ip_id',
            'ips',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-iob-os_id',
            'ip_os_browser',
            'os_id',
            'oss',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-iob-browser_id',
            'ip_os_browser',
            'browser_id',
            'browsers',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-uniq-iob',
            'ip_os_browser',
            ['ip_id', 'os_id', 'browser_id'],
            true
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ip_os_browser');
    }
}
