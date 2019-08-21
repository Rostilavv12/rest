<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

abstract class BaseController extends ActiveController
{

    public $modelClass = '';

    public $publicActions = [];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'optional' => $this->publicActions,
            'except' => ['options'],
        ];

        return $behaviors;
    }

    /**
     * @param $action
     * @param $result
     * @return mixed
     */
    public function afterAction($action, $result)
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->headers['Authorization'] = 'Bearer ' . Yii::$app->user->identity->generateToken();
        }

        return parent::afterAction($action, $result);
    }
}
