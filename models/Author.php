<?php

namespace app\models;

use yii\db\ActiveRecord;


class Author extends  ActiveRecord
{
  public static function tableName()
  {
    return 'author';
  }

  public function rules(): array
  {
    return [
      [['name', 'birthday_date'], 'required'],
      [['birthday_date'], 'date', 'format' => 'php:Y-m-d'],
      [['name', 'nationality'], 'string', 'max' => 255],
    ];
  }


  public function getBooks()
  {
    return $this->hasMany(Book::class, ['id' => 'book_id'])
      ->viaTable('author_book', ['author_id' => 'id']);
  }
}
