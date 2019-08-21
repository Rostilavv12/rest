<?php

namespace app\modules\v1\actions\auth;

use app\models\User;
use yii\rest\Action;
use Yii;

/**
 * Class SignIn
 * @package app\modules\v1\actions\auth
 */
class LoginAction extends Action
{
    /**
     * @SWG\Post(
     *     path="/v1/auth/login",
     *     tags={"auth"},
     *     summary="Authentication on the site",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *          name="data",
     *          in="body",
     *          description="Authentication Data",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *                   @SWG\Property(property="email", type="string", example="some@exs.com" ),
     *                   @SWG\Property(property="password", type="string", example="somepassword" ),
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
     *         response = 401,
     *         @SWG\Schema(
     *          type="object",
     *              @SWG\Property(
     *              property="errors",
     *              type="object",
     *                  @SWG\Property(
     *                      property="login",
     *                      type="array",
     *                      @SWG\Items(type="string", example="login is not a valid email address." ),
     *                  ),
     *              ),
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
        $pass = Yii::$app->request->post('password');
        $email = Yii::$app->request->post('email');
        $user = User::findByEmail($email);

        if (!$user || !$user->validatePassword($pass)) {
            Yii::$app->getResponse()->setStatusCode(401);

            return ['errors' => ['login' => [Yii::t('app', 'Invalid login or password')]]];
        }

        Yii::$app->getResponse()->setStatusCode(200);

        return [
            'profile' => $user->getProfile(),
            'access-token' => $user->generateToken(),
        ];
    }
}