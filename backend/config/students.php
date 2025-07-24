<?php
// config/students.php

return [
    /*
    |--------------------------------------------------------------------------
    | Student Default Settings
    |--------------------------------------------------------------------------
    |
    | Default values for student registration and profile settings
    |
    */

    'default_contact_method' => env('STUDENT_DEFAULT_CONTACT_METHOD', 'whatsapp'),

    'contact_methods' => [
        'phone',
        'email',
        'whatsapp'
    ],

    'validation' => [
        'phone_max_length' => 20,
        'name_max_length' => 100,
        'password_min_length' => 6,
    ],

    'registration' => [
        'auto_approve' => env('STUDENT_AUTO_APPROVE', true),
        'require_parent_phone' => env('STUDENT_REQUIRE_PARENT_PHONE', false),
    ]
];
