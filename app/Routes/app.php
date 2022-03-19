<?php

$app->get('/')
    ->callback(['HelloWorldController::hello_world_action'])
    /* ->name('hello_world') */;