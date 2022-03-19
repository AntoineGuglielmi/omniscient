<?php

// function ploup()
// {
//     global $omniscient;
// }

function clean_scandir($dir,...$exceptions)
{
    $exceptions = array_merge(['.','..'],$exceptions);
    return array_filter($dir,function($v) use($exceptions)
    {
        return !in_array($v,$exceptions);
    });
}

function env($env)
{
    return ENVIRONMENT === $env;
}

function dev()
{
    return env('development');
}

function prod()
{
    return env('production');
}