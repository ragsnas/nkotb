<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;
define('STDIN',fopen("php://stdin","r"));
$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

if (function_exists('apc_store')) {
    $apcLoader = new ApcClassLoader('sf2 '.sha1(__FILE__), $loader);
    $loader->unregister();
    $apcLoader->register(true);
}

require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__ . '/../app/AppCache.php';

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);