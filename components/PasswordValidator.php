<?php

namespace app\components;

use kartik\password\StrengthValidator;
use Yii;

class PasswordValidator extends StrengthValidator
{
    public $symbols = true;
    public $symbolsError = self::SYMBOLS_ERROR_HINT;

    const RULE_SYMBOLS = 'symbols';
    const SYMBOLS_ERROR_HINT = 'Latin letters, numbers and special characters are allowed: ( . , : ; ? ! * + % - < > @ [ ] { } / \ _ $ # )';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$_rules[self::RULE_SYMBOLS] = ['match' => '/^[a-zA-Z0-9\(.,:;\?\!*\+%-<>@\[\]{}\/\\\_\$\#\)]+$/i', 'bool' => true];
        $this->preset = 'normal';
        $this->min = 8;
        $this->minError = Yii::t('app', 'You have entered only {found} characters. The minimum password length is 8 characters.');
        $this->upper = 1;
        $this->upperError = Yii::t('app', 'Password must have at least 1 capital Latin letter');
        $this->digit = 1;
        $this->digitError = Yii::t('app', 'Password must have at least 1 digit');
        $this->lower = 1;
        $this->lowerError = Yii::t('app', 'Password must have at least 1 lowercase Latin letter');
        $this->special = 1;
        $this->specialError = Yii::t('app', 'Password must contain at least one of the following characters: ( . , : ; ? ! * + % - < > @ [ ] { } / \ _ {} $ # )');
        $this->hasUserError = Yii::t('app', 'Password cannot contain email');
        $this->hasEmailError = Yii::t('app', 'Password cannot contain email');
    }
}
