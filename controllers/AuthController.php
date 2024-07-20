<?php

namespace app\controllers;

use app\components\ResponseFormatter;
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

    if (!$user || !$user->validatePassword($password)) {
      throw new UnauthorizedHttpException('Invalid credentials');
    }

    $user->generateAuthToken();

    if (!$user->save(false)) {
      throw new ServerErrorHttpException('Failed to save user token.');
    }

    return ResponseFormatter::success([
      'accessToken' => $user->access_token,
      'expiresAt' => $user->token_expiry
    ],"Logged in User");
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

    $user = new User();
    $user->username = $username;
    $user->password = Yii::$app->security->generatePasswordHash($password);
    $user->auth_key = Yii::$app->security->generateRandomString();
    $user->generateAuthToken();

    if (!$user->save()) {
      throw new ServerErrorHttpException('Failed to create the user for unknown reason.');
    }

    return ResponseFormatter::success([
      'accessToken' => $user->access_token,
      'expiresAt' => $user->token_expiry
    ],"User Register");
  }
}
