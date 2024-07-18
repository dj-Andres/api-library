<?php

namespace app\controllers;

use yii\rest\ActiveController;

class AuthController extends ActiveController
{
  public $modelClass = 'app\models\Author';
}