<?php
// first creat square.php file in config then add this line 
return [
    'env' => env('SQUARE_ENV', 'sandbox'),
    'access_token' => env('SQUARE_ACCESS_TOKEN'),
    'app_id' => env('SQUARE_APP_ID'),
    'location_id' => env('SQUARE_LOCATION_ID'),
];