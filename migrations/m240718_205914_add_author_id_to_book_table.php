<?php

use yii\db\Migration;

/**
 * Class m240718_205914_add_author_id_to_book_table
 */
class m240718_205914_add_author_id_to_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%book}}', 'author_id', $this->integer()->notNull()->after('id'));

        $this->addForeignKey(
            'fk-book-author_id',
            '{{%book}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-book-author_id',
            '{{%book}}'
        );
        $this->dropColumn('{{%book}}', 'author_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240718_205914_add_author_id_to_book_table cannot be reverted.\n";

        return false;
    }
    */
}
