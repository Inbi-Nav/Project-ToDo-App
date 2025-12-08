<?php

// Enable errors during development
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// --------------------------------------------------
// DEFINE PATHS BASED ON YOUR PROJECT STRUCTURE
// --------------------------------------------------

// Backend/
define('ROOT_PATH', realpath(__DIR__ . '/..'));

// Backend/web/
define('WEB_ROOT', __DIR__);

// Backend/lib/base
define('LIB_PATH', ROOT_PATH . '/lib/base');

// Backend/controllers
define('CONTROLLER_PATH', ROOT_PATH . '/controllers');

// Backend/models
define('MODEL_PATH', ROOT_PATH . '/models');

// Backend/views
define('VIEW_PATH', ROOT_PATH . '/views');

// Backend/config
define('CONFIG_PATH', ROOT_PATH . '/config');

// --------------------------------------------------
// LOAD ROUTES (FIXED PATH)
// --------------------------------------------------

$routes = include(CONFIG_PATH . '/routes.php');

// --------------------------------------------------
// AUTOLOADER FOR CONTROLLERS, MODELS, LIB
// --------------------------------------------------

spl_autoload_register(function ($className) {

    // Controller?
    if (str_ends_with($className, 'Controller')) {
        $file = CONTROLLER_PATH . '/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // Model?
    $modelFile = MODEL_PATH . '/' . $className . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }

    // Framework /lib/base classes
    $baseFile = LIB_PATH . '/' . $className . '.php';
    if (file_exists($baseFile)) {
        require_once $baseFile;
        return;
    }

    // Debug if missing
    echo "<h3>Class not found: $className</h3>";
});

// --------------------------------------------------
// ROUTER LOGIC
// --------------------------------------------------

// Example: GET /users
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Clean request path
$request = $requestUri ?: '/';

// Route must exist
if (!isset($routes[$request])) {
    echo "404: Route not found ($request)";
    exit();
}

// Example route: "user#index"
list($controller, $action) = explode('#', $routes[$request]);

$controllerClass = ucfirst($controller) . 'Controller';
$actionMethod   = $action . 'Action';

// --------------------------------------------------
// RUN CONTROLLER ACTION
// --------------------------------------------------

$controllerObj = new $controllerClass();
$controllerObj->$actionMethod();

