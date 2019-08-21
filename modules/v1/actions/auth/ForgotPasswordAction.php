<?php

namespace app\modules\v1\actions\auth;

use app\models\User;
use app\validators\UserValidator;
use Yii;
use yii\rest\Action;

/**
 * Class ForgotPasswordAction
 * @package app\modules\v1\actions\auth
 */
class ForgotPasswordAction extends Action
{
    /**
     * @SWG\Post(
     *     path="/v1/auth/forgot-password",
     *     tags={"auth"},
     *     summary="Send reset token by email",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *          name="data",
     *          in="body",
     *          description="Authentication Data",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *                   @SWG\Property(property="email", type="string", example="some@exs.com" ),
     *          ),
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "User's reset token send to mail",
     *     ),
     *     @SWG\Response(
     *         response = 422,
     *         @SWG\Schema(
     *          type="object",
     *              @SWG\Property(
     *                  property="email",
     *                  type="array",
     *                  @SWG\Items(type="string", example="Email is not a valid email address." ),
     *              ),
     *          ),
     *         description = "Incorrect user's credentials",
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Not send email",
     *     ),
     * )
     */
    /**
     * @return mixed
     * @throws \Exception
     */
    public function run()
    {
        $validator = new UserValidator([
            'scenario' => UserValidator::SCENARIO_ONLY_EMAIL_FIND_USER,
            'email' => Yii::$app->request->post('email'),
        ]);

        if (!$validator->validate()) {
            Yii::$app->response->setStatusCode(422);

            return $validator->errors;
        }

        User::newResetToken($validator->email);

        Yii::$app->response->setStatusCode(200);
    }
}