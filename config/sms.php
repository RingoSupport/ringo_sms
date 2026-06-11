<?php

return [

    'tables' => [

        'users' => env(
            'SMS_USERS_TABLE',
            'users'
        ),

        'login_otps' => env(
            'SMS_LOGIN_OTPS_TABLE',
            'login_otps'
        ),

        'api_clients' => env(
            'SMS_API_CLIENTS_TABLE',
            'api_clients'
        ),

        'wallets' => env(
            'SMS_WALLETS_TABLE',
            'wallets'
        ),

        'wallet_history' => env(
            'SMS_WALLET_HISTORY_TABLE',
            'wallet_history'
        ),

        'messages' => env(
            'SMS_MESSAGES_TABLE',
            'messages'
        ),

        'client_sms_pricing' => env(
            'SMS_CLIENT_SMS_PRICING_TABLE',
            'client_sms_pricing'
        ),

        'provider_sms' => env(
            'SMS_PROVIDER_SMS_TABLE',
            'provider_sms'
        ),

    ],

];
