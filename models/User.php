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
            [['auth_key', 'access_token', 'keycloak_id'], 'string'],
        ];
    }


    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function getUsername()
    {
        return $this->name;
    }

    public static function findByKeycloakId($keycloakId)
    {
        return static::findOne(['keycloak_id' => $keycloakId]);
    }

    public function beforeSave($insert)
    {
        if ($insert && empty($this->auth_key)) {
            $this->auth_key = \Yii::$app->security->generateRandomString();
        }

        return parent::beforeSave($insert);
    }
}
