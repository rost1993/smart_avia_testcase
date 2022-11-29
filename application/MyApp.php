<?php

namespace MyApplication;

/*
	Базовый класс, который содержит всю информацию о запущенном экземпляре приложения

	Copyright: Rostislav Gashin, rost1993
*/
class MyApp {

	// Служебный массив со всеми настройками приложения
	public static $app = null;

	// Массив с маршрутами
	public static $routes = [
		'GET' => [],
		'POST' => [],
	];

	// Название сессии по умолчанию
	public static $defaultSessionName = 'TEST_CASE';

	public static function configure($object, $properties) {
		foreach ($properties as $name => $value)
			$object->$name = $value;
		return $object;
	}
}
