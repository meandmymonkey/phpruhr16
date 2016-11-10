<?php

use Pimple\Container;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;
use Workshop\Legacy\Wrapper;

$container = new Container();


$container['router'] = function() use ($container) {
    $locator = new FileLocator(__DIR__.'/../config');
    $router = new Router(
        new YamlFileLoader($locator),
        'routing.yml',
        [
            'cache_dir' => __DIR__.'/../cache',
            'debug' => true
        ]
    );
    
    return $router;
};


$container['controller.legacy'] = function() use ($container) {
    return new Wrapper(__DIR__.'/../legacy', $container);
};


$container['controller.test'] = function() use ($container) {
    return function() {
        return new Response('test');
    };
};


return $container;