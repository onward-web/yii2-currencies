<?php

class m160405_122901_currency_init extends \yii\db\Migration {

    public function up() {
        $tableOptions = null;
        if (Yii::$app->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%system_currency}}', [
            'code' => $this->string(10)->notNull(),
            'symbol' => $this->string(10)->notNull(),
            'name' => $this->string(255)->notNull(),
            'rate' => $this->float(5),
            'is_active' => $this->integer(1)->defaultValue(0),
            'is_default' => $this->integer(1)->defaultValue(0)
                ], $tableOptions);
        $this->addPrimaryKey('pk-system_currency', '{{%system_currency}}', ['code']);
        $this->insert('{{%system_currency}}', [
            'code' => 'USD',
            'symbol' => '$',
            'name' => 'United States Dollar',
            'rate' => '1',
            'is_active' => true,
            'is_default' => true
        ]);
    }

    public function down() {
        $this->dropTable('{{%system_currency}}');
    }

}
