<?php

session_start();

define('D', DIRECTORY_SEPARATOR);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('SERVER_LOCATION','live');
define('SERVER_PORT',$_SERVER['SERVER_PORT']);

include ROOT.D.'app/'.D.'core'.D.'base'.D.'REST.php';

$config = REST::loadxml(ROOT.D.'app'.D.'core'.D.'etc'.D.'config.xml');
define('APP_NAME',$config->name);
define('APP_VERSION',$config->version);
REST::setAuth((Array) $config->auth);
define('AUTH_HASH', $config->auth->hash);
define('AUTH_HASH_TEXT', $config->auth->hash_text);

// Save Database credentials
REST::setDatabase((Array) $config->database);

// Save url Parameters in REST class
$getUrl = isset($_GET['url']) ? $_GET['url'] : '/';
REST::setUrlParams([
    "url"       => ltrim($_SERVER['REQUEST_URI'],'/'),
    "params"    => explode('/',ltrim($getUrl, '/')),
    "exploded"  => explode('/',ltrim($_SERVER['REQUEST_URI'], '/')),
    "query"     => $_SERVER['QUERY_STRING'],
    "qExploded" => explode('/', ltrim($_SERVER['QUERY_STRING'],'url=')),
//    "redirect"  => [
//        "url"   => $_SERVER['REDIRECT_URL'],
//        "query" => $_SERVER['REDIRECT_QUERY_STRING']
//    ]
]);


// Developer Mode
REST::devMode(true);
// Use in Developer Mode
if (isset($_GET['hashify']) && DEV_MODE) echo sha1($_GET['hashify']);

// Run REST-let Application
REST::run();
