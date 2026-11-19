<?php

return [
    'required' => ':attribute sahəsi mütləqdir.',
    'email' => ':attribute düzgün e-poçt ünvanı olmalıdır.',
    'string' => ':attribute mətn olmalıdır.',
    'numeric' => ':attribute rəqəm olmalıdır.',
    'unique' => ':attribute artıq istifadə olunub.',

    'min' => [
        'string' => ':attribute ən azı :min simvol olmalıdır.',
        'numeric' => ':attribute ən azı :min olmalıdır.',
    ],

    'max' => [
        'string' => ':attribute maksimum :max simvol ola bilər.',
        'numeric' => ':attribute maksimum :max ola bilər.',
    ],

    'confirmed' => ':attribute təsdiqi uyğun gəlmir.',

    'attributes' => [
        'name' => 'ad',
        'email' => 'e-poçt',
        'password' => 'şifrə',
        'password_confirmation' => 'şifrə təsdiqi',
        'price' => 'qiymət',
        'quantity' => 'miqdar',
    ],
];
