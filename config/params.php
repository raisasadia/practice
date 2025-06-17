<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'keycloak' => [
        'realm' => 'fortifymis',
        'client_id' => 'fortify-cli',
        'base_url' => 'https://dfqt.moind.gov.bd/iam/realms/fortifymis',
        'token_url' => 'https://dfqt.moind.gov.bd/iam/realms/fortifymis/protocol/openid-connect/token',
        'auth_url' => 'https://dfqt.moind.gov.bd/iam/realms/fortifymis/protocol/openid-connect/auth',
        'userinfo_url' => 'https://dfqt.moind.gov.bd/iam/realms/fortifymis/protocol/openid-connect/userinfo',
        'logout_url' => 'https://dfqt.moind.gov.bd/iam/realms/fortifymis/protocol/openid-connect/logout',
    ],

];
