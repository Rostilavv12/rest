<?php

namespace app\modules\v1\actions\auth;

use app\models\User;
use app\validators\UserValidator;
use yii\rest\Action;
use Yii;

/**
 * Class ResetPasswordAction
 * @package app\modules\v1\actions\auth
 */
class ResetPasswordAction extends Action
{
    /**
     * @SWG\Post(
     *     path="/v1/auth/reset-password",
     *     tags={"auth"},
     *     summary="Reset password on the site",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *          name="data",
     *          in="body",
     *          description="Authentication Data",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *                   @SWG\Property(property="resetToken", type="string", example="resetToken" ),
     *                   @SWG\Property(property="password", type="string", example="newpassword" ),
     *                   @SWG\Property(property="passwordConfirm", type="string", example="newpassword" ),
     *          ),
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         @SWG\Schema(
     *          type="object",
     *              @SWG\Property(
     *              property="profile",
     *              type="object",
     *                   @SWG\Property(property="email", type="string", example="some@exs.com" ),
     *                   @SWG\Property(property="username", type="string", example="username" ),
     *              ),
     *              @SWG\Property(property="access-token", type="string", example="some_bearer_token" ),
     *          ),
     *         description = "User's profile and token",
     *     ),
     *     @SWG\Response(
     *         response = 422,
     *         @SWG\Schema(
     *          type="object",
     *                  @SWG\Property(
     *                      property="password",
     *                      type="array",
     *                      @SWG\Items(type="string", example="password is not a valid email address." ),
     *                  ),
     *          ),
     *         description = "Incorrect user's credentials",
     *     ),
     * )
     */
    /**
     * @return mixed
     * @throws \Exception
     */
    public function run()
    {
        $token = Yii::$app->request->post('resetToken');
        User::checkResetToken($token);

        $validator = new UserValidator([
            'scenario' => UserValidator::SCENARIO_ONLY_PASSWORD,
            'password' => Yii::$app->request->post('password'),
            'passwordConfirm' => Yii::$app->request->post('passwordConfirm')
        ]);

        if (!$validator->validate()) {
            Yii::$app->response->setStatusCode(422);

            return $validator->errors;
        }

        $user = User::changePassword($token, $validator->password);

        return [
            'profile' => $user->getProfile(),
            'access-token' => $user->generateToken(),
        ];
    }
}