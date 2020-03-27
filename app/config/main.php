<?php

define('DEBUG', true);
define('DS', DIRECTORY_SEPARATOR);

$basePath = realpath(__DIR__ . DS . '..' . DS . '..') . DS;
$appPath = $basePath . 'app' . DS;

$mainConfig = [
    'basePath' => $basePath,
    'appPath' => $appPath,
    'controllers_dir' => $appPath . 'controllers' . DS,
    'models_dir' => $appPath . 'models' . DS,
    'views_dir' => $appPath . 'views' . DS,
    'error_controller' => 'error',
    'error_action' => 'notfound',
    'controller_request_param' => 'controller',
    'action_request_param' => 'action',
    'default_controller' => 'catalog',
    'default_action' => 'list',
    'layout_dir' => $appPath . 'views' . DS . 'layouts' . DS,
    'db' => include __DIR__ . DS . 'pgconf.php'
];

return $mainConfig;