<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;


class AuthController extends Controller
{
  public function actionLogin()
  {
    $request = Yii::$app->request;
    $username = $request->post('username');
    $password = $request->post('password');

    $user = User::findByUsername($username);

    if ($user && $user->validatePassword($password)) {
      $user->generateAuthToken();
      $user->save();

      return [
        'status' => 'success',
        'accessToken' => $user->auth_token,
        'expiresAt' => $user->token_expiry->format('Y-m-d H:i:s')
      ];
    } else {
      throw new UnauthorizedHttpException('Invalid credentials');
    }
  }
  public function actionRegister()
  {
    $request = Yii::$app->request;
    $username = $request->post('username');
    $password = $request->post('password');

    if (User::findByUsername($username)) {
      throw new UnauthorizedHttpException('Username already exists');
    }

    if ($username === null || $password === null) {
      throw new BadRequestHttpException('Username and password cannot be null');
    }

    //$user = User::createUser($username, $password);
    $user = new User();
    $user->username = $username;
    $user->password = Yii::$app->security->generatePasswordHash($password);
    $user->authKey = Yii::$app->security->generateRandomString();
    $user->generateAuthToken();

    if (!$user->save()) {
      throw new ServerErrorHttpException('Failed to create the user for unknown reason.');
    }

    return [
      'accessToken' => $user->access_token,
      'expiresAt' => $user->token_expiry
    ];
  }
}
