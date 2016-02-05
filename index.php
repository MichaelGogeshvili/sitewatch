<?php
require '.ZEND_PREAMBLE.index.include';
$application = new Zend_Application( APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$appBootstrap = $application->bootstrap();
$appBootstrap ->run();

