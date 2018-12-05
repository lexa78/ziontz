<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ips`.
 */
class m181204_152911_create_ips_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ips', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(15)->notNull(),
        ]);

        $this->createIndex(
            'idx-uniq-ip',
            'ips',
            'ip',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ips');
    }
}
