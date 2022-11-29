<?php

namespace MyApplication\core;

use MyApplication\MyApp;

/*
	Класс для взимодействия с сессиями
	Позволяет осуществлять все действия с сессиями
	Настройки сессии указываются в конфигурационном файле config/web.php -> параметр session

	Copyright: Rostislav Gashin (rost1993)
*/
class MyApplicationSession {

	// Название сессии
	protected $name = '';

	// Ключ шифрования
	protected $cipher = '';

	// Контрольная фраза подписи
	protected $secret = '';

	// Флаг активирован ли режим шифрования сессий или нет
	protected $validation = true;

	/*
		Конструктор класса для взаимодействия с сессиями
		Производит считывание данных с конфигурационного файла и запись в служебные переменные
	*/
	public function __construct($configure = []) {

		if(!empty($configure)) {
			$this->name = (empty($configure['name']))
							? MyApp::$defaultSessionName
							: $configure['name'];

			$this->validation = (array_key_exists('validation', $configure)
							 && (is_bool($configure['validation']) === true))
								? $configure['validation']
								: true;

			$this->cipher = (empty($configure['cipher']))
							? ''
							: $configure['cipher'];

			$this->secret = (empty($configure['secret']))
							? ''
							: $configure['secret'];
		} else {
			$this->name = MyApp::$defaultSessionName;
			$this->cipher = $this->secret = '';
		}

		$this->checkSettingsSession();
	}

	/*
		Функция проверки настроек для класса сессий
		Главная проверка заключается в том чтобы проверить если активирован режим шифрования сессий (по умолчанию он активирован)
		то необходимо проверить переданы ли параметры 'cipher' и 'secret' в файле настроек веб-приложения
		Если процедура проверки не будет пройдена тогда будет сгенерирован Exception и вызвана функция error с выводом текста ошибок
		Будет осуществлен выход из приложения
	*/
	private function checkSettingsSession() {
		try {
			if($this->validation && empty($this->cipher))
				throw new \Exception("Error in module 'Session'! The field 'cipher' must not be empty!");

			if($this->validation && empty($this->secret))
				throw new \Exception("Error in module 'Session! The field 'secret' must not be empty!");
		} catch(\Exception $e) {
			//\IcKomiApp::error($e->getMessage());
		}
	}

	// Старт интерфейса для взаимодействия с массивами сессий
	public function start() {
		if(!$this->isStarted()) {
			session_name($this->name);
			session_start();
		}
	}

	/*
		Проверка запущена сессия или нет
		Возвращаемое значение: TRUE - запущена, FALSE - не запущена
	*/
	private function isStarted() {
		if(!(session_status() === PHP_SESSION_ACTIVE))
			return false;

		if(!(session_name() === $this->name)) {
			$this->destroy();
			return false;
		}
		return true;
	}

	/*
		Функция получения значения из массива сессий
		$name - название параметра
		$default_value - значение по умолчанию, которое будет возвращено в случае если параметр $name не будет найден в массиве
		$case - оператор перевода в нижний или верхний регистр. По умолчанию -1, значит массив сессий подвергать процедура перевода в регистр не надо
		Возвращаемое значение: FALSE - если сессии не стартовали, значение массива сессий или $default_value
	*/
	public function get($name, $default_value = null, $case = -1) {
		if(!$this->isStarted())
			return false;

		$session = ($case === -1) ? $_SESSION : array_change_key_case($_SESSION, $case);
		return (empty($session[$name])) ? $default_value : $session[$name];
	}

	/*
		Установить значение в массив сессий
		$name - название параметра
		$value - значение параметра
	*/
	public function set($name, $value = '') {
		if($this->isStarted())
			$_SESSION[$name] = $value;
	}

	/*
		Удаление параметра в массиве сессий
		$name - название параметра
	*/
	public function del($name) {
		if($this->isStarted())
			unset($_SESSION[$name]);
	}

	// Уничтожение данных массива сессий и уничтожение сведений о сессии
	public function destroy() {
		if(session_status() === PHP_SESSION_ACTIVE) {
			$_SESSION = [];
			session_unset();
			session_destroy();
		}
	}

	// Очистка данных массива сессий
	public function clear() {
		if($this->isStarted())
			unset($_SESSION);
	}

	// Перезапустить сессию веб-ресурса
	public function restart() {
		$this->destroy();
		$this->start();
	}

	// Получить сессионные данные в виде массива
	public function getArraySession($case = -1) {
		if($this->isStarted())
			return ($case == -1) ? $_SESSION : array_change_key_case($_SESSION, $case);
	}

	// Запись изменений и закрытие сеанса работы с классом сессий
	public function commit() {
		if($this->isStarted())
			session_write_close();
	}

	/*
		Проверка существует или нет параметр $name в массиве сессий
		$name - название параметр
		Возвращает: TRUE - существует, FALSE - не существует
	*/
	public function has($name) {
		return empty($_SESSION[$name]) ? false : true;
	}
}