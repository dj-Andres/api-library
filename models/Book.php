<?php

namespace app\models;

use yii\db\ActiveRecord;


class Book extends ActiveRecord
{
  public static function tableName()
  {
    return 'book';
  }

  /**
   * Validate the information the requeste
   * @return array
   */
  public function rules(): array
  {
    return [
      [['title', 'publication_year','author_id'], 'required', 'message' => '{attribute} no puede estar vacío.'],
      [['publication_year'], 'integer', 'message' => '{attribute} debe ser un número entero.'],
      [['publication_year'], 'integer', 'min' => 0, 'message' => '{attribute} debe ser un número positivo.'],
      [['description'], 'string', 'message' => '{attribute} debe ser un texto.'],
      [['title'], 'string', 'max' => 255, 'message' => '{attribute} no debe exceder los 255 caracteres.'],
  ];
  }

  public function getAuthors()
  {
    return $this->hasMany(Author::class, ['id' => 'author_id'])
      ->viaTable('author_book', ['book_id' => 'id']);
  }

  public function fields()
  {
    $fields = parent::fields();
    $fields['authors'] = function ($model) {
      return $model->authors;
    };
    return $fields;
  }
}
