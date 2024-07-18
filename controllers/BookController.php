<?php

namespace app\controllers;

use app\models\Book;
use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class BookController extends ActiveController
{
  public $modelClass = 'app\models\Book';

  public function actions()
  {
    $actions = parent::actions();
    // Disable the "delete" and "update" actions provided by ActiveController
    unset($actions['delete'], $actions['update']);
    return $actions;
  }
  /**
   * Get all books
   * @return array
   */
  public function actionIndex(): array
  {
    return Book::find()->all();
  }
  /**
   * Get data by Id
   * @param int $id
   */
  public function actionView(int $id)
  {
    return $this->findModel($id);
  }
  public function actionCreate()
  {
    $model = new Book();
    $model->load(Yii::$app->request->post(), '');

    if ($model->save()) {
      $autorIds = Yii::$app->request->post('authors');
      if (!empty($autorIds)) {
        foreach ($autorIds as $autorId) {
          Yii::$app->db->createCommand()->insert('author_book', [
            'author_id' => $autorId,
            'book_id' => $model->id,
          ])->execute();
        }
      }
      return $model;
    } else {
      return $model->errors;
    }
  }
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);
    $model->load(Yii::$app->request->post(), '');

    if ($model->save()) {
      Yii::$app->db->createCommand()->delete('author_book', ['book_id' => $model->id])->execute();

      $autorIds = Yii::$app->request->post('authors', []);
      foreach ($autorIds as $autorId) {
        Yii::$app->db->createCommand()->insert('author_book', [
          'author_id' => $autorId,
          'book_id' => $model->id,
        ])->execute();
      }
      return $model;
    } else {
      return $model->errors;
    }
  }
  public function actionDelete(int $id)
  {
    $model = $this->findModel($id);
    Yii::$app->db->createCommand()->delete('author_book', ['book_id' => $model->id])->execute();
    return $model->delete();
  }

  protected function findModel($id)
  {
    if (($model = Book::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('The requested book does not exist.');
  }
}
