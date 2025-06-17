<?php
namespace app\components;

use yii\authclient\OpenIdConnect;

class KeycloakClient extends OpenIdConnect
{
    public $name = 'keycloak';
    public $title = 'Login with Keycloak';

    public function init()
    {
        parent::init();
        $this->issuerUrl = 'https://dfqt.moind.gov.bd/iam' . '/realms/' . 'fortifymis';
    }
}
