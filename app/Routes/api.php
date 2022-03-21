<?php

$api->get('/([a-z]+)')
    ->callback(['HelloWorldController::hello_world_action'])
    /* ->name('hello_world') */;