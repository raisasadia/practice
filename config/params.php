<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'keycloak' => [
        'realm' => 'myrealm',
        'client_id' => 'yii-client',
        'base_url' => 'http://localhost:8081/realms/myrealm',
        'token_url' => 'http://localhost:8081/realms/myrealm/protocol/openid-connect/token',
        'auth_url' => 'http://localhost:8081/realms/myrealm/protocol/openid-connect/auth',
        'userinfo_url' => 'http://localhost:8081/realms/myrealm/protocol/openid-connect/userinfo',
        'logout_url' => 'http://localhost:8081/realms/myrealm/protocol/openid-connect/logout',
        'redirect_uri' => 'http://localhost:8080/site/callback',
        'redirect_uri_after_logout' => 'http://localhost:8080',
    ],

];
