<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\User;
use app\validators\UserValidator;
use yii\console\Controller;

/**
 * This command generate default users
 */
class UserController extends Controller
{
    /**
    {
        "email": "admin@admin.com",
        "password": "DpkC2w!3%fr*e*lNd0sMubKl"
    }
     */
    private $users = [
        [
            'email' => 'admin@admin.com',
            'password' => 'DpkC2w!3%fr*e*lNd0sMubKl',
            'username' => 'First Admin Ivanovich',
        ],
    ];

    public function actionGenerateDefault()
    {
        echo 'start generate default users' . PHP_EOL;

        $userModel = new User();

        foreach ($this->users as $user) {
            echo '  create for user ' . $user['email'];

            $userModel->createNewUser(new UserValidator($user));

            if ($userModel->save()) {
                echo ' SUCCESS' . PHP_EOL;
            } else {
                echo ' FAIL' . PHP_EOL;
            }
        }

        echo 'end generate default users' . PHP_EOL;
    }
}
