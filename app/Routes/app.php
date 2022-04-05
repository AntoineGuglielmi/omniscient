<?php

$app->get('/costs/rand')
    ->callback(['BackController::insert_random_cost'])
    /* ->name('hello_world') */;

$app->get('/')->callback(['HelloWorldController::debug']);