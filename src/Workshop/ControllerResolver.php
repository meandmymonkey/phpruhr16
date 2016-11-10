<?php

namespace Workshop;

use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ControllerResolver implements ControllerResolverInterface#
{
    /**
     * @var Container
     */
    private $container;
    
    const PREFIX = 'controller.';

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getController(Request $request)
    {
        $serviceId = self::PREFIX.$request->attributes->get('_controller');
        $action = $request->attributes->get('_action');
        
        if (!isset($this->container[$serviceId])) {
            return false;
        }
        
        return [$this->container[$serviceId], $action];
    }

    public function getArguments(Request $request, $controller)
    {
        return [$request];
    }
}