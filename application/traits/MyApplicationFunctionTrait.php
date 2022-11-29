<?php

namespace IcKomi\core;

/*
	Служебный trait, содержащий вспомогательные служебные функции, которые могут использоваться в различных классах
	не нарушая при этом правил наследования

	Copyright: Rostislav Gashin, rost1993
*/
trait IcKomiFunctionTrait {

	public static $BLACK_MAGIC_CONSTANT = 'Copyright:SuperRost1993';

	/*
		Функция вывода ошибок. Производит форматированный вывод ошибок с вывовдом типа данных
		После вывода объекта функция завершает работу скрипта. И производит выход из приложения
		$object - объект, который необходимо вывести на экран
	*/
	public static function debug($object) {
		echo '<pre>';
		var_dump($object);
		echo '</pre>';
		exit;
	}

	/*
		Функция вывода сообщения пользователю об ошибке, которая обработана данным фреймворком
		$errorMessage - сообщение которое необходимо вывести пользователю
	*/
	public static function error($errorMessage) {
		print_r('IC Komi MVC framework generate error:');
		echo '<pre>';
		print_r($errorMessage);
		echo '</pre>';
		exit;
	}

	/*
		Проверка массива $arr является ли ассоциативным массивом или нет
		$arr - проверяемый массив
		Возвращаемое значение: TRUE - ассоциативный, FALSE - не ассоциативный
	*/
	public static function isAssoc(array $arr) {
		if (array() === $arr)
			return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	/*
		Функция записи ошибок в лог файл
		$errorMessage - ошибка. Может быть строкой либо массивом. Если будет передан массив то тогда функция заишет в файл "key - value"
		$filename - название файла в который нужно поместить лог файл
		$mode - режим открытия файла (см. справоки по PHP какие режимы открытия файла существуют)
	*/
	public static function debugInFile($errorMessage, $filename = null, $mode = null) {
		$filename = ($filename === null) ? "../debug/debug.log" : "../debug/" . $filename;
		$mode = ($mode === null) ? 'a' : $mode;

		$fp = fopen($filename, $mode);

		if(is_array($errorMessage)) {
			foreach ($errorMessage as $key => $value)
				fwrite($fp, $key . ' - ' . $value . "\r\n");
		} else {
			fwrite($fp, $errorMessage . "\r\n");
		}

		fclose($fp);
	}

	public static function generateUniqHash($randomWord = '') {
		$current_date = date('Y') . "-" . date('m') . "-" . date('d') . "-" . date('h-i-s');

		if(empty($randomWord))
			return $hash = hash('sha256', $current_date . self::$BLACK_MAGIC_CONSTANT);
		
		$hash = hash('sha256', $randomWord . $current_date . self::$BLACK_MAGIC_CONSTANT);
		return $hash;
	}

	// Функция конвертирования даты из формата MySQL в нормальный человеческий вид
	public static function convertToMySQLDateFormat($date) {
		if(mb_strlen(trim($date)) == 0)
			return null;

		if(preg_match('/\d\d\d\d\-\d\d\-\d\d/ui', $date) == 1)
			return $date;
		
		$newDate = mb_substr($date, 6, 4);
		$newDate .= "-" . mb_substr($date, 3, 2);
		$newDate .= "-" . mb_substr($date, 0, 2);
		return $newDate;
	}

	// Функция конвертирования даты из формата MySQL в нормальный человеческий вид
	public static function convertToDate($date) {
		if(mb_strlen(trim($date)) == 0)
			return null;
		
		$newDate = mb_substr($date, 8, 2);
		$newDate .= "." . mb_substr($date, 5, 2);
		$newDate .= "." . mb_substr($date, 0, 4);
		return $newDate;
	}

	public static function calculateOffsetPagination($page) {
		return \IcKomiApp::$app->maxPagination * ($page - 1);
	}
}
