<?php

namespace app\modules\v1\controllers;

use app\modules\v1\actions\auth\CheckResetTokenAction;
use app\modules\v1\actions\auth\ForgotPasswordAction;
use app\modules\v1\actions\auth\LoginAction;
use app\modules\v1\actions\auth\LogoutAction;
use app\modules\v1\actions\auth\ResetPasswordAction;

class AuthController extends BaseController
{
    public function init()
    {
        parent::init();

        $this->modelClass = 'app\models\User';
        $this->publicActions = ['login', 'reset-password', 'forgot-password', 'check-reset-token'];
    }

    public function actions()
    {
        return array_merge(
            parent::actions(),
            [
                'login' => [
                    'class' => LoginAction::class,
                    'modelClass' => $this->modelClass
                ],
                'logout' => [
                    'class' => LogoutAction::class,
                    'modelClass' => $this->modelClass
                ],
                'reset-password' => [
                    'class' => ResetPasswordAction::class,
                    'modelClass' => $this->modelClass
                ],
                'forgot-password' => [
                    'class' => ForgotPasswordAction::class,
                    'modelClass' => $this->modelClass
                ],
                'check-reset-token' => [
                    'class' => CheckResetTokenAction::class,
                    'modelClass' => $this->modelClass
                ],
            ]
        );
    }
}
