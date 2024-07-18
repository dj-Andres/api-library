<?php

namespace app\components;

use app\models\User;
use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

class AuthFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        $headers = Yii::$app->request->headers;
        $token = $headers->get('Authorization');

        if (!$token) {
            throw new UnauthorizedHttpException('Token not provided');
        }

        $user = User::findIdentityByAccessToken($token);
        if (!$user) {
            throw new UnauthorizedHttpException('Invalid token');
        }

        Yii::$app->user->login($user);
        return parent::beforeAction($action);
    }
}
