<?php

$app->get('/costs/rand')
    ->callback(['BackController::insert_random_cost'])
    /* ->name('hello_world') */;

$app->get('/')->callback(['HelloWorldController::debug']);

// $app->get('/table/([a-z\-A-Z0-9]+)')->callback(['HelloWorldController::get_table']);