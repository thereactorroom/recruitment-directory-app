<?php

require_once 'fmini/libs/Utils.php';
require_once 'fmini/libs/Curl.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/envconfig.php';

$env = strpos($_SERVER['DOCUMENT_ROOT'], "uat")
    ? 'sandbox'
    : 'production';

$envHost = config['env']['online'][$env]['host'];
$envPath = $_ENV['ROOT_PATH'];

$moduleHost = $envHost . '/modules/module_dev/recruitment_directory/v1';
$modulePath = $envPath . '/modules/module_dev/recruitment_directory/v1';

return [
    'env' => $env,
    'envHost' => $envHost,
    'envPath' => $envPath,
    'moduleHost' => $moduleHost,
    'modulePath' => $modulePath,
];

