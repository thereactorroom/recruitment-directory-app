<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
header("Content-Type: application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = require 'bootstrap.php';

define('ENV', $config['env']);
define('ENV_HOST', $config['envHost']);
define('ENV_PATH', $_ENV['ROOT_PATH'] . "/modules/module_dev/");
define('SANDBOX', $config['env'] == 'sandbox' ? true : false);

define('PAYFAST_MERCHANT_ID', '31910631'); # 31910631 - 10042359
define('PAYFAST_MERCHANT_KEY', 'xjetc45basfod');
define('PAYFAST_PASSPHRASE', '1L0veW/tching/nim-');
define('PAYFAST_PROCESS_URL', 'https://www.payfast.co.za/onsite/process');

$params = null;
$request_method = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_method = "post";
    $params = (object) $_POST;
} else {
    $request_method = "get";
    $params = (object) $_GET;
}

$url = "";
if (isset($_GET['url'])) {
    $url = rtrim($_GET['url'], '/');
} else {
    $url = "index";
}
$args = explode('/', $url);

define("DS", DIRECTORY_SEPARATOR);
define("PHP_EXT", ".php");
define("ROOT_PATH", getcwd(). DS);
define("FMINI_PATH", ROOT_PATH . "fmini" . DS);

define("CORE_PATH", FMINI_PATH . "core" . DS);
define("LIBS_PATH", FMINI_PATH . "libs" . DS);
define("CONFIG_PATH", FMINI_PATH . "config" . DS);
define("MODELS_PATH", FMINI_PATH . "models" . DS);
define("CONTROLLER_PATH", FMINI_PATH . "controllers" . DS);
define("SERVICES_PATH", FMINI_PATH . "services" . DS);

spl_autoload_register(function ($class) {
    if (file_exists(CORE_PATH . $class . PHP_EXT)) {
        require_once CORE_PATH . $class . PHP_EXT;
    }
    if (file_exists(CONFIG_PATH . $class . PHP_EXT)) {
        require_once CONFIG_PATH . $class . PHP_EXT;
    }
    if (file_exists(LIBS_PATH . $class . PHP_EXT)) {
        require_once LIBS_PATH . $class . PHP_EXT;
    }
    if (file_exists(MODELS_PATH . $class . PHP_EXT)) {
        require_once MODELS_PATH . $class . PHP_EXT;
    }
    if (file_exists(CONTROLLER_PATH . $class . PHP_EXT)) {
        require_once CONTROLLER_PATH . $class . PHP_EXT;
    }
    if (file_exists(SERVICES_PATH . $class . PHP_EXT)) {
        require_once SERVICES_PATH . $class . PHP_EXT;
    }
});

// print_r($args);
$controller_name = ucfirst(array_shift($args)) . "Controller";
$controller_file = CONTROLLER_PATH . $controller_name . PHP_EXT;
// print_r($args);
$method = array_shift($args);
if ($method === null) {
    $method = "index";
}
// print_r($args);
// echo $controller_name;
// echo $method;
// die();

if (file_exists($controller_file)) {
    require_once $controller_file;
    $controller = new $controller_name($params, $request_method);
    if (method_exists($controller, $method)) {
        echo $controller->{$method}();
    } else {
        echo json_encode([
            'status' => false,
            'message' => "Controller method ($method) does not exists"
        ]);
        die(404);
    }
} else {
    echo json_encode([
        'status' => false,
        'message' => "Controller ($controller_name) does not exists "
    ]);
    die(404);
}
