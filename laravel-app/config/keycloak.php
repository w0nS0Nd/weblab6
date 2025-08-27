<?php

return [
    'realm_public_key' => env('KEYCLOAK_REALM_PUBLIC_KEY', ''),
    'token_encryption_algorithm' => env('KEYCLOAK_TOKEN_ENCRYPTION_ALGORITHM', 'RS256'),
    'load_user_from_database' => env('KEYCLOAK_LOAD_USER_FROM_DATABASE', false),
    'user_provider_credential' => env('KEYCLOAK_USER_PROVIDER_CREDENTIAL', 'username'),
    'token_principal_attribute' => env('KEYCLOAK_TOKEN_PRINCIPAL_ATTRIBUTE', 'preferred_username'),
    'append_decoded_token' => env('KEYCLOAK_APPEND_DECODED_TOKEN', true),
    'allowed_resources' => env('KEYCLOAK_ALLOWED_RESOURCES', 'subscriber-app'),
    'ignore_resources_validation' => env('KEYCLOAK_IGNORE_RESOURCES_VALIDATION', false),
    'leeway' => env('KEYCLOAK_LEEWAY', 60),
    'input_key' => env('KEYCLOAK_INPUT_KEY', null),
];
