<?php

return [
    'client_id'         => env('SSO_APP_ID'),
    'client_secret'     => env('SSO_APP_SECRET') ?? env('JWT_SECRET'),
    'sso_server_url'    => env('SSO_SERVER_URL'),
    'client_type'       => env('SSO_APP_TYPE') ?? 'TOKEN' // TOKEN or SESSION
];