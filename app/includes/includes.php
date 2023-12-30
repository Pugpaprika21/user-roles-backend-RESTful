<?php

$env = parse_ini_file(__DIR__ . '../../../.env');

define('APP_ROOT', __DIR__ . '../../util/');

require APP_ROOT . 'functions/app.php';
require APP_ROOT . 'database/Redbean.php';
require APP_ROOT . 'container/app.php';
