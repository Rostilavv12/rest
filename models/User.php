<?php

namespace app\models;

use app\models\BaseModels\UserModel;
use app\validators\UserValidator;
use Firebase\JWT\JWT;
use Yii;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\IdentityInterface;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends UserModel implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $token
     * @param null $type
     *
     * @return User|IdentityInterface|null
     *
     * @throws UnauthorizedHttpException
     * @throws UnprocessableEntityHttpException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $decoded = JWT::decode($token, Yii::$app->params['jwt']['key'], [Yii::$app->params['jwt']['algorithm']]);

        $condition = [
            'id' => $decoded->iss,
            'email' => $decoded->iat,
            'auth_key' => $decoded->aud,
        ];

        if (isset($decoded->exp) && time() > $decoded->exp) {
            throw new UnauthorizedHttpException(Yii::t('app', 'Token is overdue'));
        }

        return self::findOne($condition);
    }

    /**
     * Generate new user's access token
     *
     * @param null $nbf
     * @param bool $rememberMe
     *
     * @return string
     */
    public function generateToken($rememberMe = false)
    {
        $continue = $rememberMe ? Yii::$app->params['jwt']['lifetimeRememberMe'] : Yii::$app->params['jwt']['lifetime'];
        $token = [
            'iss' => $this->id,
            'iat' => $this->email,
            'aud' => $this->auth_key,
            'userName' => $this->username,
            'exp' => isset(Yii::$app->params['jwt']['exp']) ? (time() + $continue) : null,
        ];

        return JWT::encode($token, Yii::$app->params['jwt']['key'], Yii::$app->params['jwt']['algorithm']);
    }

    /**
     * @param $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        return static::findOne(['password_reset_token' => $token]);
    }

    /**
     * validate reset token
     *
     * @param $token
     *
     * @return User
     *
     * @throws BadRequestHttpException
     * @throws UnauthorizedHttpException
     */
    public static function checkResetToken($token)
    {
        $partToken = explode('_', $token);

        if (empty($partToken[1]) || time() > $partToken[1]) {
            throw new UnauthorizedHttpException(Yii::t('app', 'Reset token is overdue'));
        }

        $user = self::findByPasswordResetToken($token);

        if (!$user) {
            throw new BadRequestHttpException(Yii::t('app', 'Not valid request'));
        }

        return $user;
    }

    /**
     * change user pass and login from all devices
     *
     * @param $token
     * @param $password
     *
     * @return User
     *
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     */
    public static function changePassword($token, $password)
    {
        $user = self::findByPasswordResetToken($token);

        $user->setPassword($password);
        $user->removePasswordResetToken();

        $user->logout(Yii::t('app', 'Failed to save password for unknown reason.'));

        return $user;
    }

        /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(64);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString(64) . '_' .
            (time() + Yii::$app->params['jwt']['lifetimeResetPass']);
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Set user data to model
     *
     * @param UserValidator $validator
     *
     * @throws \yii\base\Exception
     */
    public function createNewUser(UserValidator $validator)
    {
        $this->generateAuthKey();
        $this->setPassword($validator->password);
        $this->email = $validator->email;
        $this->username = $validator->username;
    }

    /**
     * @param UserValidator $validator
     *
     * @return $this
     *
     * @throws UnprocessableEntityHttpException
     */
    public function updateProfile(UserValidator $validator)
    {
        $this->username = $validator->username;
        $this->email = $validator->email;

        if (!$this->save()) {
            throw new UnprocessableEntityHttpException(Json::encode($this->errors));
        }

        return $this;
    }

    public function getProfile()
    {
        return [
            'email' => $this->email,
            'username' => $this->username,
        ];
    }

    /**
     * @param string $email
     *
     * @return bool
     *
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public static function newResetToken($email)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $user = self::findByEmail($email);
        $user->generatePasswordResetToken();

        if (!$user->validate() || !$user->save()) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException(Yii::t('app', 'Not save reset token'));
        }

        if (!$user->sendToken('email-reset-password', Yii::t('app', 'Reset Your Password'))) {
            $transaction->rollBack();
            throw new ServerErrorHttpException(Yii::t('app', 'Not send email'));
        }
        $transaction->commit();

        return true;
    }

    /**
     * @param string $view
     * @param string $subject
     *
     * @return bool
     */
    public function sendToken($view, $subject)
    {
        return Yii::$app->mailer->compose(
            [
                'html' => $view
            ],
            [
                'code' => $this->password_reset_token,
            ])
            ->setFrom(Yii::$app->params['fromEmail'])
            ->setTo($this->email)
            ->setSubject($subject)
            ->send();
    }

    public function logout($errorMess)
    {
        $this->generateAuthKey();

        if (!$this->save()) {
            throw new ServerErrorHttpException($errorMess);
        }
    }

}
