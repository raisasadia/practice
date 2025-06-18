<?php
namespace app\components\Keycloak;

class Keycloak
{
    public static function auth()
    {
        return new AuthService();
    }

    public static function user()
    {
        return new UserService();
    }
}
