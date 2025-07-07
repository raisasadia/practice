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

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }
    public function getUsername()
    {
        return $this->name;
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public static function createFromKeycloak($keycloakUser)
    {
        $user = new self();
        $user->name = $keycloakUser['name'] ?? $keycloakUser['preferred_username'];
        $user->email = $keycloakUser['email'];
        $user->keycloak_id = $keycloakUser['sub'];
        $user->email = $keycloakUser['email'];
        $user->save(false);
        return $user;
    }

        public function getIsAdmin()
    {
        return $this->user_role === 'admin';
    }

}
