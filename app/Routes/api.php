<?php

// $api->get('/([a-z]+)')
//     ->callback(['HelloWorldController::hello_world_action'])
//     /* ->name('hello_world') */;

// $api->get('/budgets-home')
//     ->callback(['BudgetsController::get_home']);

// $api->get('/teeest/([a-zA-Z0-9%*()]+)')
    // ->callback(['ApiController::teeest']);


    
// depences
// $api->get('/all/([a-zA-Z0-9_]+)')
//     ->callback(['ApiController::get_all']);

// $api->get('/budgets_state')
//     ->callback(['BudgetsController::get_all']);

// $api->get('/budgets_gestion')
//     ->callback(['BudgetsController::get_all']);

// $api->delete('/budgets/delete/([0-9]+)')
//     ->callback(['BudgetsController::deleteById']);

// $api->put('/budgets/move/([0-9]+)/([a-z]+)')
//     ->callback(['BudgetsController::move']);

// $api->put('/budgets/add')
//     ->callback(['BudgetsController::add']);


// $api->get('/budgets')
//     ->callback(['BudgetsController::get_all']);

// $api->get('/budgets/([0-9]+)')
//     ->callback(['BudgetsController::get_by_id']);

// $api->get('/([a-z\-]+)')
//     ->callback(['ApiController::get_all']);

// $api->get('/budgets/([0-9\-]+)/costs')
//     ->callback(['CostsController::get_all_by_budgetId']);

// $api->post('/budgets/([0-9]+)')
//     ->callback(['BudgetsController::update']);

// $api->post('/budgets')
//     ->callback(['BudgetsController::add']);

// $api->delete('/budgets/([0-9]+)')
//     ->callback(['BudgetsController::deleteById']);

$api->get('/budgets')
    ->callback(['BudgetsController::get_budgets']);

$api->get('/budgets/([0-9]+)')
    ->callback(['BudgetsController::get_budgets']);

$api->get('/config')
    ->callback(['ConfigsController::get_all']);

$api->get('/nav-links')
    ->callback(['NavLinksController::get_all']);



$api->delete('/budgets/([0-9]+)')
    ->callback(['BudgetsController::delete_budget']);



$api->put('/budgets/([0-9]+)')
    ->callback(['BudgetsController::update_budget']);



$api->post('/budgets')
    ->callback(['BudgetsController::create_budgets']);