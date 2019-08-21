<?php

namespace app\modules\v1\actions\auth;

use app\models\User;
use Yii;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class SignIn
 * @package app\modules\v1\actions\auth
 */
class CheckResetTokenAction extends Action
{
    /**
     * @SWG\Get(
     *     path="/v1/auth/check-reset-token/?token={token}",
     *     tags={"auth"},
     *     summary="Check reset token on the site",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *          name="token",
     *          in="path",
     *          description="Reset token",
     *          type="string",
     *          required=true,
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *          @SWG\Schema(
     *          type="object",
     *              @SWG\Property(
     *                  property="reset-token",
     *                  type="string",
     *                  example="6SHhcJXGusD0fOjMDMOM4gGtVrBv4wpo7OTWbrHz1KN7mbEmG-SKAJX6Pq7QOwLm_1560524549",
     *              ),
     *          ),
     *         description = "Reset token valid",
     *     ),
     *     @SWG\Response(
     *         response = 400,
     *         description = "Not valid request",
     *     ),
     *     @SWG\Response(
     *         response = 401,
     *         description = "Reset token is overdue",
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Don't check token by server reason",
     *     ),
     * )
     */
    /**
     * @return mixed
     * @throws \Exception
     */
    public function run()
    {
        $user = User::checkResetToken(Yii::$app->request->get('token'));

        $user->generatePasswordResetToken();

        if ($user->save()) {
            throw new ServerErrorHttpException(Yii::t('app', 'Don\'t check token by server reason'));
        }

        return ['reset-token' => $user->password_reset_token];
    }
}