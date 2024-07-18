<?php

namespace app\controllers;

use app\components\ResponseFormatter;
use app\models\Book;
use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class BookController extends ActiveController
{

  /**
   * @var string the class model is using the controller.
   */
  public $modelClass = 'app\models\Book';

  /**
   * Setting the actions defaults the contoller
   * @return array Las acciones configuradas the actions settings.
   */
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
    $books = Book::find()->all();
    return ResponseFormatter::success($books);
  }
  /**
   * Get detail the book for a specific id
   * @param int $id
   * @return array 
   * @throws NotFoundHttpException
   */
  public function actionView(int $id)
  {
    $book = $this->findModel($id);
    return ResponseFormatter::success($book);
  }
  /**
   * Create a new book
   * @return array 
   * @throws UnprocessableEntityHttpException the validation fail.
   */
  public function actionCreate()
  {
    $model = new Book();
    $model->load(Yii::$app->request->post(), '');

    if ($model->validate() && $model->save()) {
      $autorIds = Yii::$app->request->post('authors');
      if (!empty($autorIds)) {
        foreach ($autorIds as $autorId) {
          Yii::$app->db->createCommand()->insert('author_book', [
            'author_id' => $autorId,
            'book_id' => $model->id,
          ])->execute();
        }
      }
      Yii::$app->response->statusCode = 201;
      return ResponseFormatter::success($model, 'Created book successfully.');
    } else {
      throw new UnprocessableEntityHttpException(json_encode($model->errors));
    }
  }
  /**
   * Updated the data the book.
   * @param int $id
   * @return array 
   * @throws UnprocessableEntityHttpException the validations faild.
   * @throws NotFoundHttpException If the book not fount.
   */
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);
    $model->load(Yii::$app->request->post(), '');

    if ($model->validate() && $model->save()) {
      Yii::$app->db->createCommand()->delete('author_book', ['book_id' => $model->id])->execute();

      $autorIds = Yii::$app->request->post('authors', []);
      foreach ($autorIds as $autorId) {
        Yii::$app->db->createCommand()->insert('author_book', [
          'author_id' => $autorId,
          'book_id' => $model->id,
        ])->execute();
      }
      return ResponseFormatter::success($model, 'Updated book successfully.');
    } else {
      throw new UnprocessableEntityHttpException(json_encode($model->errors));
    }
  }
  /**
   * Deleted a book
   * @param int $id
   * @return array 
   * @throws UnprocessableEntityHttpException If not deleted the book.
   * @throws NotFoundHttpException If not found the book.
   */
  public function actionDelete(int $id)
  {
    $model = $this->findModel($id);
    Yii::$app->db->createCommand()->delete('author_book', ['book_id' => $model->id])->execute();

    if ($model->delete() === false) {
      throw new UnprocessableEntityHttpException('No se pudo eliminar el libro.');
    }
    Yii::$app->response->statusCode = 204;
    return ResponseFormatter::success(null, 'Book deleted.');
  }

  /**
   * Search the model for the specified id
   * @param int $id.
   * @return Book
   * @throws NotFoundHttpException If not found the book.
   */
  protected function findModel($id)
  {
    if (($model = Book::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('The requested book does not exist.');
  }
}
