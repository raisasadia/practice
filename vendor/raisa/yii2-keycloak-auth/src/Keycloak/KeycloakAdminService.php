<?php
namespace yii2keycloak\Keycloak;

use GuzzleHttp\Client;

class KeycloakAdminService
{
    protected $realm;
    protected $baseUrl;

    public function __construct()
    {
        $this->realm = \Yii::$app->params['keycloak']['realm'];
        $this->baseUrl = rtrim(\Yii::$app->params['keycloak']['base_url'], '/');
    }

    protected function getAdminToken()
    {
        $client = new Client();

        $response = $client->post($this->baseUrl . '/realms/' . $this->realm . '/protocol/openid-connect/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => \Yii::$app->params['keycloak']['admin_client_id'],
                'client_secret' => \Yii::$app->params['keycloak']['admin_client_secret']
            ]
        ]);

        return json_decode($response->getBody(), true)['access_token'];
    }

    public function getAllUsers()
    {
        $token = $this->getAdminToken();

        $client = new Client();
        $response = $client->get($this->baseUrl . '/admin/realms/' . $this->realm . '/users', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

}