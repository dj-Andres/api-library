<?php

namespace app\controllers;

use app\components\AuthFilter;
use app\components\ResponseFormatter;
use app\models\Author;
use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class AuthorController extends ActiveController
{

  /**
   * @var string the class model is using the controller.
   */
  public $modelClass = 'app\models\Author';

  public function behaviors()
  {
        return [
            'authenticator' => [
                'class' => AuthFilter::class,
            ],
        ];
  }

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
   * Get all authors
   * @return array the list of books
   */
  public function actionIndex(): array
  {
    $authors = Author::find()
      ->all();
    return ResponseFormatter::success($authors);
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
   * Create a new Author
   * @return array 
   * @throws UnprocessableEntityHttpException the validation fail.
   */
  public function actionCreate()
  {
    $model = new Author();
    $model->load(Yii::$app->request->post(), '');

    if ($model->validate() && $model->save()) {
      Yii::$app->response->statusCode = 200;
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
   * @throws NotFoundHttpException If the author not fount.
   */
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);
    $model->load(Yii::$app->request->post(), '');

    if ($model->validate() && $model->save()) {
      return ResponseFormatter::success($model, 'Updated author successfully.');
    } else {
      throw new UnprocessableEntityHttpException(json_encode($model->errors));
    }
  }
  /**
   * Deleted a book
   * @param int $id
   * @return array 
   * @throws UnprocessableEntityHttpException If not deleted the book.
   * @throws NotFoundHttpException If not found the author.
   */
  public function actionDelete(int $id)
  {
    $model = $this->findModel($id);
    if ($model->delete() === false) {
      throw new UnprocessableEntityHttpException('No se pudo eliminar el autor.');
    }
    Yii::$app->response->statusCode = 204;
    return ResponseFormatter::success(null, 'Author deleted.');
  }
  /**
   * Search the model for the specified id
   * @param int $id.
   * @return Author
   * @throws NotFoundHttpException If not found the author.
   */
  protected function findModel($id)
  {
    if (($model = Author::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('The requested author does not exist.');
  }
}
