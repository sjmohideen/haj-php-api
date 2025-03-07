<?php

use Models\Db;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';
ob_start();
session_start();
header("Access-Control-Allow-Origin: *");

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . '/../src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../src/customer-routes.php';
$routes($app);

$routes = require __DIR__ . '/../src/login-routes.php';
$routes($app);

$routes = require __DIR__ . '/../src/message-routes.php';
$routes($app);

$routes = require __DIR__ . '/../src/message-bulk-upload.php';
$routes($app);

$routes = require __DIR__ . '/../src/haji-info-routes.php';
$routes($app);
$routes = require __DIR__ . '/../src/master-data-routes.php';
$routes($app);

$routes = require __DIR__ . '/../src/sticker-routers.php';
$routes($app);



// Run app
$app->run();
// Specify domains from which requests are allowed

header('Access-Control-Allow-Origin: *');




// Specify which request methods are allowed
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');


// Additional headers which may be sent along with the CORS request
// The X-Requested-With header allows jQuery requests to go through
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');