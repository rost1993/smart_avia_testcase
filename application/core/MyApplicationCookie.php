<?php

namespace MyApplication\core;

/*
	Класс для взаимодействия с данными COOKIE
	Позволяет добавлять, изменять, удалять данные COOKIE

	Класс содержит некоторые свойства, которыми может управлять пользователь.
	Более подробно перечень свойств и их значение можно ознакомиться в справочном руководстве PHP (setcookie)

	Copyright: Rostislav Gashin, rost1993
*/
class MyApplicationCookie {

	// Время жизни COOKIE
	protected $expires = 60*60*24*30*12;
	
	// Путь к директории на сервере из которой будут доступны COOKIE
	protected $path = '/';
	
	// Домен к которому привязан COOKIE
	protected $domain = '';
	
	// Передавать COOKIE только по шифрованному каналу
	protected $secure = false;
	
	// Передавать COOKIE только посредством HTML
	protected $httponly = false;
	
	// Ключ шифрования
	protected $cipher = '';

	// Секретная фраза ждя подписи COOKIE
	protected $secret = '';

	// Флаг, необходимо ли шифровать COOKIE
	protected $validation = false;

	/*
		Конструктор класса COOKIE.
		Конструктор работает в 2-х режимах. Если передан массив с настройками $configure то настройки будут загружены из данного массива
		Если какие-то параметры отсутствуют, то будут выбраны значения по умолчанию

		Если массив с настройками $configure не будет передан, то настройки будут захвачены из конфигурационного файла /config/web.php
		$configure - массив с настройками для работы с COOKIE.
		Example: $configure = [ 'expires' => 0, 'path' => '/' ]
	*/
	public function __construct($configure = []) {
		if(!empty($configure)) {
			$this->expires = (empty($configure['expires'])) ? $this->expires : $configure['expires'];
			$this->path = (empty($configure['path'])) ? $this->path : $configure['path'];
			$this->domain = (empty($configure['domain'])) ? $this->domain : $configure['domain'];
			$this->secure = (empty($configure['secure'])) ? $this->secure : $configure['secure'];
			$this->httponly = (empty($configure['httponly'])) ? $this->httponly : $configure['httponly'];
			$this->cipher = (empty($configure['cipher'])) ? $this->cipher : $configure['cipher'];
			$this->secret = (empty($configure['secret'])) ? $this->secret : $configure['secret'];
			$this->validation = (empty($configure['validation'])) ? $this->validation : $configure['validation'];
		}

		$this->checkSettingsCookie();
	}

	/*
		Функция проверки настроек для класса сессий
		Главная проверка заключается в том чтобы проверить если активирован режим шифрования сессий (по умолчанию он активирован)
		то необходимо проверить переданы ли параметры 'cipher' и 'secret' в файле настроек веб-приложения
		Если процедура проверки не будет пройдена тогда будет сгенерирован Exception и вызвана функция error с выводом текста ошибок
		Будет осуществлен выход из приложения
	*/
	private function checkSettingsCookie() {
		try {
			if($this->validation && empty($this->cipher))
				throw new \Exception("Error in module 'Cookie'! The field 'cipher' must not be empty!");

			if($this->validation && empty($this->secret))
				throw new \Exception("Error in module 'Cookie! The field 'secret' must not be empty!");	
		} catch(\Exception $e) {
			var_dump($e);
		}
	}

	/*
		Получение значение массива COOKIE
		$name - название параметра
		$default_value - значение по умолчанию, которое будет возврашено если значение параметра в массиве найдено не будет
		$case - флаг в каком регистре работать с массивом COOKIE. По умолчанию -1, работать с оригинальным массивом
		Возвращаемое значение: null - если параметр не найден, или значение параметра
	*/
	public function get($name, $default_value = null, $case = -1) {
		$cookie = ($case == -1) ? $_COOKIE : array_change_key_case($_COOKIE, $case);

		return (empty($_COOKIE[$name])) ? $default_value : $_COOKIE[$name];
	}

	/*
		Установить значение параметра в массив COOKIE
		$name - название параметра
		$value - значение параметра
		$cookie - класс Cookie для установки нового COOKIE. По умолчанию null (если необходимо использовать базовые настройки)
		Если же необходимо использовать какие-то специфичные настройки для установки COOKIE,
		то необходимо инициировать класс Cookie с указание заданных настроек (см. объявление данного класса с использованием $configure)
	*/
	public function set($name, $value, $cookie = null) {
		$cookie = ($cookie == null) ? $this : $cookie;
		setcookie($name, $value, time() + $cookie->expires, $cookie->path, $cookie->domain, $cookie->secure, $cookie->httponly);
	}

	/*
		Проверка существует или нет указанный параметр в массиве COOKIE
		$name - название параметра
		Возвращаемое значение: TRUE - существует, FALSE - не существует
	*/
	public function has($name) {
		return !(empty($_COOKIE[$name]));
	}

}