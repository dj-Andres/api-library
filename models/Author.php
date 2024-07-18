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
      [['name', 'birthday_date'], 'required', 'message' => '{attribute} no puede estar vacío.'],
      [['birthday_date'], 'date', 'format' => 'php:Y-m-d','message' => '{attribute} Debe de ser una fecha valida.'],
      [['name', 'nationality'], 'string', 'max' => 255,'message' => '{attribute} no debe exceder los 255 caracteres.'],
    ];
  }


  public function getBooks()
  {
    return $this->hasMany(Book::class, ['id' => 'book_id'])
      ->viaTable('author_book', ['author_id' => 'id']);
  }
}
