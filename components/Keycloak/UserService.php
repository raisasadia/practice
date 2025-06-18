<?php
namespace app\components\Keycloak;

use GuzzleHttp\Client;

class UserService
{
    public function getUserInfo($accessToken)
    {
        $client = new Client();
        $response = $client->get(\Yii::$app->params['keycloak']['userinfo_url'], [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
