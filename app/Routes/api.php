<?php

$api->get('/([a-z]+)')
    ->callback(['HelloWorldController::hello_world_action'])
    /* ->name('hello_world') */;

$api->get('/budgets-home')
    ->callback(['BudgetsController::get_home'])
    /* ->name('hello_world') */;