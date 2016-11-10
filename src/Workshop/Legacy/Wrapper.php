<?php

namespace Workshop\Legacy;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Wrapper
{
    private $legacyBasePath;

    /**
     * @param $legacyBasePath
     */
    public function __construct($legacyBasePath)
    {
        $this->legacyBasePath = $legacyBasePath;
    }

    public function __invoke(Request $request)
    {
        $legacyScript = $this->legacyBasePath
            .DIRECTORY_SEPARATOR
            .$request->getPathInfo();
        
        if (!file_exists($legacyScript)) {
            return new Response('Not found.', 404);
        }
        
        return StreamedResponse::create(
            function() use ($request, $legacyScript) {
                $_SERVER['PHP_SELF'] = $legacyScript;
                $_SERVER['SCRIPT_NAME'] = $request->getPathInfo();
                $_SERVER['SCRIPT_FILENAME'] = $legacyScript;
                
                chdir(dirname($legacyScript));
                
                require $legacyScript;
            }
        );
    }
}