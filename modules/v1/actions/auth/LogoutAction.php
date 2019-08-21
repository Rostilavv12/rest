<?php

namespace app\modules\v1\actions\auth;

use Yii;
use yii\rest\Action;

/**
 * Class SignIn
 * @package app\modules\v1\actions\auth
 */
class LogoutAction extends Action
{
    /**
     * @SWG\Post(
     *     path="/v1/auth/logout",
     *     summary="Logout current user from all devices",
     *     tags={"auth"},
     *     @SWG\Response(
     *         response=200,
     *         description="ok",
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Failed to logout for unknown reason.",
     *     )
     * )
     */
    public function run()
    {
        Yii::$app->user->identity->logout(Yii::t('app', 'Failed to logout for unknown reason.'));

        Yii::$app->user->identity = null;

        Yii::$app->getResponse()->setStatusCode(200);
    }
}