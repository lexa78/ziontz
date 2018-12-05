<?php

use yii\db\Migration;

/**
 * Handles the creation of table `browsers`.
 */
class m181204_153508_create_browsers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('browsers', [
            'id' => $this->primaryKey(),
            'browser' => $this->string(40)->notNull(),
        ]);

        $this->createIndex(
            'idx-uniq-browser',
            'browsers',
            'browser',
            true
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('browsers');
    }
}
