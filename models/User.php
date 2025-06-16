<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['name', 'password'], 'required'],
            [['email'], 'email'],
        ];
    }

    // IdentityInterface methods below

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // Implement if needed
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null; // Implement if needed
    }

    public function validateAuthKey($authKey)
    {
        return false; // Implement if needed
    }
    public function getUsername()
    {
        return $this->name;
    }
}
