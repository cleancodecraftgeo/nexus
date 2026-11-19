<?php

return [
    'required' => ':attribute ველი სავალდებულოა.',
    'email' => ':attribute უნდა იყოს სწორი ელფოსტის მისამართი.',
    'string' => ':attribute უნდა იყოს ტექსტი.',
    'numeric' => ':attribute უნდა იყოს რიცხვი.',
    'unique' => ':attribute უკვე გამოყენებულია.',

    'min' => [
        'string' => ':attribute უნდა შეიცავდეს მინიმუმ :min სიმბოლოს.',
        'numeric' => ':attribute უნდა იყოს მინიმუმ :min.',
    ],

    'max' => [
        'string' => ':attribute შეიძლება შეიცავდეს მაქსიმუმ :max სიმბოლოს.',
        'numeric' => ':attribute შეიძლება იყოს მაქსიმუმ :max.',
    ],

    'confirmed' => ':attribute დადასტურება არ ემთხვევა.',

    'attributes' => [
        'name' => 'სახელი',
        'email' => 'ელფოსტა',
        'password' => 'პაროლი',
        'password_confirmation' => 'პაროლის დადასტურება',
        'price' => 'ფასი',
        'quantity' => 'რაოდენობა',
    ],
];
