<?php

namespace Workshop\Legacy;

use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Wrapper
{
    private $legacyBasePath;
    
    private $container;

    /**
     * @param $legacyBasePath
     */
    public function __construct($legacyBasePath, Container $container)
    {
        $this->legacyBasePath = $legacyBasePath;
        $this->container = $container;
    }

    public function indexAction(Request $request)
    {
        $legacyScript = $this->legacyBasePath
            .DIRECTORY_SEPARATOR
            .$request->attributes->get('_legacy_script');
        
        if (!file_exists($legacyScript)) {
            return new Response('Not found.', 404);
        }
        
        $router = $this->container['router'];
        
        return StreamedResponse::create(
            function() use ($request, $legacyScript, $router) {
                $_SERVER['PHP_SELF'] = $legacyScript;
                $_SERVER['SCRIPT_NAME'] = $request->attributes->get('_legacy_script');
                $_SERVER['SCRIPT_FILENAME'] = $legacyScript;
                
                chdir(dirname($legacyScript));
                
                require $legacyScript;
            }
        );
    }
}