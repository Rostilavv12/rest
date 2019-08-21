<?php

namespace app\validators;

use app\models\User;
use app\components\PasswordValidator;
use yii\base\Model;

/**
 * Provide a validation of category request (Admin|Director|Worker request)
 *
 * Class CategoryValidator
 * @package app\validators
 *
 * @property string $email
 * @property string $password
 * @property string $passwordConfirm
 * @property string $username
 */
class UserValidator extends Model
{
    const SCENARIO_ONLY_EMAIL_FIND_USER = 'onlyEmailFindUser';
    const SCENARIO_ONLY_PASSWORD = 'onlyPassword';

    public $email;
    public $password;
    public $passwordConfirm;
    public $username;

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'password', 'username'], 'string', 'max' => 255],
            [['email'], 'email'],

            [['username'], 'required',
                'on' => self::SCENARIO_DEFAULT],
            [['password'], 'required',
                'except' => self::SCENARIO_ONLY_EMAIL_FIND_USER],
            [['email'], 'required',
                'except' => self::SCENARIO_ONLY_PASSWORD],

            [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email',
                'on' => self::SCENARIO_DEFAULT],

            [['email'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'email',
                'on' => self::SCENARIO_ONLY_EMAIL_FIND_USER],

            [['password'], PasswordValidator::class, 'userAttribute' => 'email',
                'except' => self::SCENARIO_ONLY_EMAIL_FIND_USER],
            [['passwordConfirm'], 'compare', 'compareAttribute' => 'password',
                'on' => self::SCENARIO_ONLY_PASSWORD],
        ];
    }
}