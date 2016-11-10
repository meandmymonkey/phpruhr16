<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

Debug::enable(E_ALL & ~E_NOTICE);

$container = require_once __DIR__.'/../src/app.php';

$request = Request::createFromGlobals();
$response = $container['kernel']->handle($request);
$response->send();