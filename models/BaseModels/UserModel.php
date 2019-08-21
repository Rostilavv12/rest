<?php

namespace app\models\BaseModels;

use app\interfaces\UserInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use app\modules\admin\models\UserSubscriptions;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $username
 * @property int $created_at
 * @property int $updated_at
 *
 */
class UserModel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'auth_key', 'password_hash', 'username'], 'required'],
            [['email', 'auth_key', 'password_hash', 'username', 'password_reset_token'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'integer'],
            [['email'], 'unique'],
        ];
    }
}
