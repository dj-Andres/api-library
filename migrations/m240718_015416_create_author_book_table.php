<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author_book}}`.
 */
class m240718_015416_create_author_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_book}}', [
            'author_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-author_book', 'author_book', ['author_id', 'book_id']);
        $this->addForeignKey('fk-author_book-author', 'author_book', 'author_id', 'author', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-author_book-book', 'author_book', 'book_id', 'book', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-author_book-author', 'author_book');
        $this->dropForeignKey('fk-author_book-book', 'author_book');
        $this->dropTable('{{%author_book}}');
    }
}
