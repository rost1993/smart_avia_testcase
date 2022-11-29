<?php

namespace MyApplication\core;

use MyApplication\MyApp;
use MyApplication\core\MyApplicationRouter;
use MyApplication\core\MyApplicationHeaderLoader;

/*
	Базовый абстрактный класс для организация контроллера в схеме MVC.
	Контроллер предназначен для взаимодействия моделей с отображением данных (VIEW).
	Не стоит перегружать контроллер различными сложными методами. Если есь необхдимость то лучше перенести всю необходимую логику в раздел /components

	Copyright: Rostisalv Gashin (rost1993)
*/
abstract class MyApplicationController {
	
	/*
		Конструктор класса
	*/
	public function __construct() {}

	/*
		Метод передачи управления во VIEW. Данный метод использует предопределенный шаблон в который встраивается контент из $view
		Если view не будет обнаружен, то будет осуществлен редирект на страницу 404 Not found
		$view - относительный путь к странице, которую необходимо отрисовать
		$parameters - параметры, которые необходимо передать во VIEW для их обработки и отрисовки
	*/
	public function render($view, $parameters = []) {
		$file_view = '../views/' . $view . '.php';

		extract($parameters);

		if(file_exists($file_view)) {
			$header = MyApplicationHeaderLoader::getHeader();

			$title = MyApplicationHeaderLoader::getTitle();

			$favicon = MyApplicationHeaderLoader::getFavicon();

			ob_start();
			require_once($file_view);
			$content = ob_get_clean();

			require_once('../views/layout/layout.php');
		} else {
			MyApplicationRouter::redirect(MyApplicationRouter::ERROR_404);
		}
	}
}
