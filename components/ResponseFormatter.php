<?php


namespace app\components;

use Yii;

use yii\helpers\Json;

/**
 * Class ResponseComponent
 * 
 * This component is responsible for formatting the response the application
 */

class ResponseFormatter
{

  /**
   * Formatting the response successfully
   *
   * @param mixed $data the data a include in response.
   * @param string $message value the message.
   * @return array the formatting response.
   */
  public static function success($data, $message = 'Successfully')
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    return [
      'status' => Yii::$app->response->statusCode,
      'message' => $message,
      'data' => $data,
    ];
  }

  /**
   * Formatting the response error
   *
   * @param string $message the error message.
   * @param int $statusCode the code the status HTTP.
   * @return array formatting response.
   */
  public static function error($message, $statusCode)
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    return [
      'status' => Yii::$app->response->statusCode,
      'message' => $message,
      'data' => null,
    ];
  }
}
