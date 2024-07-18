<?php

namespace app\models;

use yii\db\ActiveRecord;


class Book extends ActiveRecord
{
  public static function tableName()
  {
    return 'book';
  }

  public function rules(): array
  {
    return [
      [['title', 'publication_year'], 'required'],
      [['publication_year'], 'integer'],
      [['description'], 'string'],
      [['title'], 'string', 'max' => 255],
    ];
  }

  public function getAuthors()
  {
    return $this->hasMany(Author::class, ['id' => 'author_id'])
      ->viaTable('author_book', ['book_id' => 'id']);
  }
}
