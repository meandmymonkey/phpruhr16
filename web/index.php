<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

Debug::enable(E_ALL & ~E_NOTICE);

$container = require_once __DIR__.'/../src/app.php';

$request = Request::createFromGlobals();

$attributes = $container['router']->matchRequest($request);
$request->attributes->add($attributes);

$response = call_user_func(
    $container[$attributes['_controller']],
    $request
);

$response->send();