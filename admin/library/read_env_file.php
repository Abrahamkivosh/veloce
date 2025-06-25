<?php

// read from .env file in the root directory
// check the file .env in the root directory
$path = __DIR__ . '/../../.env';
if (file_exists($path)) {
    // read from .env file into an array
    $env = parse_ini_file($path);
    // set the environment variables
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}else 
{
    die ('.env file not found');
}



