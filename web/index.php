<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use Workshop\Legacy\Wrapper;

require_once __DIR__.'/../vendor/autoload.php';

Debug::enable(E_ALL & ~E_NOTICE);

$wrapper = new Wrapper(__DIR__.'/../legacy');

$request = Request::createFromGlobals();
$response = $wrapper($request);
$response->send();