<?php

session_start();

define('D', DIRECTORY_SEPARATOR);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('SERVER_LOCATION',($_SERVER['SERVER_ADDR'] === '127.0.0.1')?'local':'live');
define('SERVER_PORT',$_SERVER['SERVER_PORT']);

include 'base/REST.php';

$config = REST::loadxml(ROOT.D.'app'.D.'core'.D.'etc'.D.'config.xml');
define('APP_NAME',$config->name);
define('APP_VERSION',$config->version);
REST::setAuth((Array) $config->auth);

// Save Database credentials
REST::setDatabase((Array) $config->database);

// Save url Parameters in REST class
REST::setUrlParams([
    "url"       => ltrim($_SERVER['REQUEST_URI'],'/'),
    "params"    => explode('/',ltrim($_GET['url'], '/')),
    "exploded"  => explode('/',ltrim($_SERVER['REQUEST_URI'], '/')),
    "query"     => $_SERVER['QUERY_STRING'],
    "qExploded" => explode('/', ltrim($_SERVER['QUERY_STRING'],'url=')),
    "redirect"  => [
        "url"   => $_SERVER['REDIRECT_URL'],
        "query" => $_SERVER['REDIRECT_QUERY_STRING']
    ]
]);


// Developer Mode
//REST::devMode(true);

// Run REST-let Application
REST::run();