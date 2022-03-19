<?php

use Antoineg\Omniscient\Core\Omniscient;

require 'vendor/autoload.php';

$omniscient = new Omniscient();

try
{
    $omniscient->go();
}
catch(Exception $e)
{
    $omniscient->exception($e);
}

// echo '<pre>';
// var_dump($omniscient);
// echo '</pre>';