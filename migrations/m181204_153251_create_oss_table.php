<?php

use yii\db\Migration;

/**
 * Handles the creation of table `oss`.
 */
class m181204_153251_create_oss_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('oss', [
            'id' => $this->primaryKey(),
            'os' => $this->string(25)->notNull(),
        ]);

        $this->createIndex(
            'idx-uniq-os',
            'oss',
            'os',
            true
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('oss');
    }
}
