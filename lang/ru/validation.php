<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения.',
    'email' => 'Поле :attribute должно быть корректным email-адресом.',
    'string' => 'Поле :attribute должно быть строкой.',
    'numeric' => 'Поле :attribute должно быть числом.',
    'unique' => 'Значение поля :attribute уже используется.',

    'min' => [
        'string' => 'Поле :attribute должно содержать минимум :min символов.',
        'numeric' => 'Поле :attribute должно быть не менее :min.',
    ],

    'max' => [
        'string' => 'Поле :attribute должно содержать максимум :max символов.',
        'numeric' => 'Поле :attribute должно быть не более :max.',
    ],

    'confirmed' => 'Подтверждение поля :attribute не совпадает.',

    'attributes' => [
        'name' => 'имя',
        'email' => 'email',
        'password' => 'пароль',
        'password_confirmation' => 'подтверждение пароля',
        'price' => 'цена',
        'quantity' => 'количество',
    ],
];
