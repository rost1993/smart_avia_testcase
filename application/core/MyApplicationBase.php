<?php

namespace MyApplication\core;

use MyApplication\core\MyApplicationRequest;
use MyApplication\core\MyApplicationSession;
use MyApplication\core\MyApplicationCookie;

/*
	Базовый класс, который инициализирует настройки экземпляра движка

	Copyright: Rostislav Gashin, rost1993
*/
class MyApplicationBase {

	// Автор данного MVC движка
	public $author = 'Rostislav Gashin (rost1993)';

	// Маршрут по умолчанию
	public $defaultRoute = 'IcKomiApp';

	// Файл шаблона
	public $defaultLayoutView = 'default';

	// Название WEB-ресурса по умолчанию
	public $defaultTitle = 'My MVC framework';

	public $request = null;

	public $session = null;

	public $cookie = null;

	// Массив с предопределнными JS-скриптами. Определены в \config\web.php
	public $js = [];

	// Массив с предопределнными CSS-скриптами. Определены в \config\web.php
	public $css = [];

	/*
		Конструктор класса
	*/
	public function __construct($configure = []) {
		$this->bootstrapJS($configure);
		$this->bootstrapCSS($configure);
		$this->bootstrapServiceModule($configure);
		$this->bootstrapUserVariables($configure);
	}

	/*
		Здесь может быть инициализация соединений с базами данных или загрузка инфомрации о пользователе
	*/
	public function init() {
	}

	/*
		Загрузка JS-файлов
	*/
	private function bootstrapJS(&$configure) {
		if(!array_key_exists('js', $configure))
			return;

		$this->js = $configure['js'];
		unset($configure['js']);
	}

	/*
		Загрузка CSS-файлов
	*/
	private function bootstrapCSS(&$configure) {
		if(!array_key_exists('css', $configure))
			return;

		$this->css = $configure['css'];
		unset($configure['css']);
	}

	/*
		Загрузка служебных классов
	*/
	private function bootstrapServiceModule(&$configure) {
		$this->request = new MyApplicationRequest();

		$config = (!array_key_exists('session', $configure)) ? [] : $configure['session'];
		$this->session = new MyApplicationSession($config);
		unset($configure['session']);

		$config = (!array_key_exists('cookie', $configure)) ? [] : $configure['cookie'];
		$this->cookie = new MyApplicationCookie();
		unset($configure['cookie']);
	}

	/*
		Загрузка пользовательских данных
	*/
	private function bootstrapUserVariables(&$configure) {
		foreach ($configure as $parameter => $value)
			$this->$parameter = $value;
	
		$configure = null;
	}
}
