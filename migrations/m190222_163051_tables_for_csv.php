<?php

use yii\db\Migration;

/**
 * Class m190222_163051_tables_for_csv
 */
class m190222_163051_tables_for_csv extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('csvs', [
            'id' => $this->primaryKey(),
            'uid' => $this->integer()->notNull(),
            'file_name' => $this->string()->notNull(),
            'fields' => $this->text(),
            'uploaded_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('csvs');
    }
}
