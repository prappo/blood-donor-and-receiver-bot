<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Facebook Token
    |--------------------------------------------------------------------------
    |
    | Your Facebook application you received after creating
    | the messenger page / application on Facebook.
    |
    */
    'token' => env('FACEBOOK_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Facebook App Secret
    |--------------------------------------------------------------------------
    |
    | Your Facebook application secret, which is used to verify
    | incoming requests from Facebook.
    |
    */
    'app_secret' => env('FACEBOOK_APP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Facebook Verification
    |--------------------------------------------------------------------------
    |
    | Your Facebook verification token, used to validate the webhooks.
    |
    */
    'verification' => env('FACEBOOK_VERIFICATION'),

    /*
    |--------------------------------------------------------------------------
    | Facebook Start Button Payload
    |--------------------------------------------------------------------------
    |
    | The payload which is sent when the Get Started Button is clicked.
    |
    */
    'start_button_payload' => 'GET_STARTED',

    /*
    |--------------------------------------------------------------------------
    | Facebook Greeting Text
    |--------------------------------------------------------------------------
    |
    | Your Facebook Greeting Text which will be shown on your message start screen.
    |
    */
    'greeting_text' => [
        'greeting' => [
            [
                'locale' => 'default',
                'text' => 'রক্ত দিন এবং নিন একটি অলাভজনক রক্ত দেওয়া নেওয়া মাধ্যম যা স্বয়ংক্রিয় ভাবে পরিচালিত',
            ],
            [
                'locale' => 'en_US',
                'text' => 'রক্ত দিন এবং নিন একটি অলাভজনক রক্ত দেওয়া নেওয়া মাধ্যম যা স্বয়ংক্রিয় ভাবে পরিচালিত',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Facebook Persistent Menu
    |--------------------------------------------------------------------------
    |
    | Example items for your persistent Facebook menu.
    | See https://developers.facebook.com/docs/messenger-platform/reference/messenger-profile-api/persistent-menu/#example
    |
    */
    'persistent_menu' => [
        [
            'locale' => 'default',
            'composer_input_disabled' => 'false',
            'call_to_actions' => [
                [
                    'title' => 'রক্ত লাগবে',
                    'type' => 'postback',
                    'payload' => 'receiver',
                ],
                [
                    'title' => 'রক্ত দিব',
                    'type' => 'postback',
                    'payload' => 'donor',
                ],
                [
                    'title' => 'অ্যাকাউন্ট সেটিংস',
                    'type' => 'nested',
                    'call_to_actions' => [
                        [
                            'title' => 'আমার অ্যাকাউন্ট',
                            'type' => 'postback',
                            'payload' => 'MY_ACCOUNT',
                        ],
                        [
                            'title' => 'রক্তদাতা হিসেবে অব্যাহতি',
                            'type' => 'postback',
                            'payload' => 'DELETE_MY_ACCOUNT',
                        ]
                    ],
                ]
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Facebook Domain Whitelist
    |--------------------------------------------------------------------------
    |
    | In order to use domains you need to whitelist them
    |
    */
    'whitelisted_domains' => [
        'https://petersfancyapparel.com',
    ],
];
