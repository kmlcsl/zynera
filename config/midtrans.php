<?php

return [

    /**
     * Set your Merchant ID
     * Get your Merchant ID from https://dashboard.midtrans.com/
     */
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),

    /**
     * Set your client key
     * Get your client key from https://dashboard.midtrans.com/
     */
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    /**
     * Set your server key
     * Get your server key from https://dashboard.midtrans.com/
     */
    'server_key' => env('MIDTRANS_SERVER_KEY'),

    /**
     * Set to true if you're using sandbox environment
     * Set to false if you're using production environment
     */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /**
     * Set sanitization to true if you want to sanitize the data
     */
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),

    /**
     * Set 3DS to true if you want to enable 3D Secure
     */
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

    /**
     * API URLs
     */
    'api_url' => env('MIDTRANS_IS_PRODUCTION', false) 
        ? 'https://api.sandbox.midtrans.com/v2/' 
        : 'https://api.sandbox.midtrans.com/v2/',

    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false) 
        ? 'https://app.sandbox.midtrans.com/snap/snap.js' 
        : 'https://app.sandbox.midtrans.com/snap/snap.js',

];