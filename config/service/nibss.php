<?php

return [
    'base_url' => env('NIBSS_BASE_URL', 'https://apitest.nibss-plc.com.ng'),
    'grant_type' => env('NIBSS_GRANT_TYPE', 'client_credentials'),
    'client_secret' => env('NIBSS_CLIENT_SECRET'),
    'client_id' => env('NIBSS_CLIENT_ID'),
    'scope' => env('NIBSS_SCOPE'),
    'api_key' => env('NIBSS_API_KEY'),
];
