<?php

namespace MyApplication\core;

use MyApplication\MyApp;
use MyApplication\core\MyApplicationRequest;

/*
	Обработчик муршрутизации

	Copyright: Rostislav Gashin (rost1993), Kotkov Anton
*/
class MyApplicationRouter {

	const ERROR_404 = 404;
	const ERROR_403 = 403;

	private $url = null;
	private $page = null;

	private $path_web_routes = null;

	private $path_web_config = null;

	/*
	*/
	public function __construct() {
		$this->getUrl();
		$this->getRouter();
	}

	/*
		Получение запрашиваемого пути
	*/
	private function getUrl() {
		$url = trim($_SERVER['REQUEST_URI']);
		$url = preg_replace('/\..*?$/i', '', $url);
		$url = preg_replace('/\?.*?$/i', '', $url);

		$this->url = $url;
	}

	/*
		Разбор маршрута на компоненты
	*/
	private function getRouter() {
		$route_array = explode('/', $this->url);

		$this->page = empty($route_array[1]) ? '' : $route_array[1];

		$this->path_web_routes = __DIR__ . '/../../routes/web.php';
	}

	/*
		Загрузка всех маршрутов приложения
	*/
	private function loadRoutes() {
		if(file_exists($this->path_web_routes)) {
			include_once($this->path_web_routes);
		}
	}

	/*
		Осуществляем редирект на страницу
	*/
	public static function redirectNotFound() {
		require_once('../views/layout/404.php');
		http_response_code(404);
		exit;
	}

	/*
		Редирект
	*/
	public static function redirect($error_code = 0, $page = '', $permission_document = '') {
		if ($error_code == self::ERROR_404) {
			require_once('../views/layout/404.php');
			http_response_code(404);
		} else {
			header('Location: ' . $page);
		}

		exit;
	}

	/*
		Детальный разбор маршрутизации
	*/
	public function run() {
		$this->loadRoutes();

		$routes = MyApplicationRequest::isGet() ? MyApp::$routes['GET'] : MyApp::$routes['POST'];

		$controller = $action = null;

		if (array_key_exists($this->url, $routes)) {
			$controller = '\controllers\\' . $routes[$this->url]['controller'];
			$action = $routes[$this->url]['action'];
		} else {
			$this->redirectNotFound();
		}

		if (!class_exists($controller)) {
			$this->redirectNotFound();
		}

		if (!method_exists($controller, $action)) {
			$this->redirectNotFound();
		}

		(new $controller())->$action();
	}
}
