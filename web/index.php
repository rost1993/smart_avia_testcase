<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../application/MyApp.php');

(new MyApplication\core\Application())->run();