<?php

namespace MyApplication\core;

use MyApplication\MyApp;

use MyApplication\core\MyApplicationBase;
use MyApplication\core\MyApplicationRouter;

/*
	Класс, инициализирующий работу приложения.
	Производит настройки приложения и загружает приложение

	Copyright: Rostislav Gashin, rost1993
*/
class Application {

	// Configurations file
	const PATH_CONFIG_FILE = __DIR__ . '/../../config/web.php';

	// Constructor class
	public function __construct() {
		$configure = require_once(self::PATH_CONFIG_FILE);

		MyApp::$app = new MyApplicationBase($configure);
		MyApp::$app->init();
	}

	// Initialization application
	public function run() {
		try {
			(new MyApplicationRouter())->run();	
		} catch(\Exception $e) {
			var_dump($e);
		}
	}

}
