<?php

return [
    'spreadsheet_id' => env('GOOGLE_SPREADSHEET_ID', ''),
    'service_account_json' => env('GOOGLE_SERVICE_ACCOUNT_JSON', storage_path('app/google-service-account.json')),
];
