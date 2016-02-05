<?php
setlocale(LC_ALL, 'ru_RU.CP1251');
define('APPLICATION_PATH', __DIR__ . '/application');
define('APPLICATION_ENV', 'production');
set_include_path(implode(PATH_SEPARATOR, array(APPLICATION_PATH . '/library', get_include_path(),)));
require_once 'Zend/Application.php';
$application = new Zend_Application( APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$appBootstrap = $application->bootstrap();
$appBootstrap ->run();

