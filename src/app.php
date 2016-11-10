<?php

use Pimple\Container;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;
use Workshop\ControllerResolver;
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


$container['dispatcher'] = function() use ($container) {
    $dispatcher = new EventDispatcher();
    $dispatcher->addSubscriber(
        new RouterListener(
            $container['router']->getMatcher(),
            new RequestStack()
        )
    );
    
    return $dispatcher;
};


$container['kernel'] = function() use ($container) {
    $resolver = new ControllerResolver($container);
    
    return new HttpKernel($container['dispatcher'], $resolver);
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