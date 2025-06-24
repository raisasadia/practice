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
  
    public function getUserById($userId)
    {
        $client = new \GuzzleHttp\Client();
        $params = \Yii::$app->params['keycloak'];

        // Get admin token
        $response = $client->post($params['token_url'], [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $params['admin_client_id'],
                'client_secret' => $params['admin_client_secret'],
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $token = $data['access_token'];

        // Get user by ID
        $response = $client->get("{$params['base_url']}/admin/realms/{$params['realm']}/users/{$userId}", [
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getUserSessions($userId)
    {
        $client = new \GuzzleHttp\Client();
        $params = \Yii::$app->params['keycloak'];

        // Get admin token
        $response = $client->post($params['token_url'], [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $params['admin_client_id'],
                'client_secret' => $params['admin_client_secret'],
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $token = $data['access_token'];

        $response = $client->get("{$params['base_url']}/admin/realms/{$params['realm']}/users/{$userId}/sessions", [
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

}