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
        $authHeader = $headers->get('Authorization');

        if (!$authHeader) {
            throw new UnauthorizedHttpException('Token not provided');
        }

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        } else {
            $token = $authHeader;
        }

        $user = User::findIdentityByAccessToken($token);
        if (!$user) {
            throw new UnauthorizedHttpException('Invalid token');
        }

        Yii::$app->user->login($user);
        return parent::beforeAction($action);
    }
}
