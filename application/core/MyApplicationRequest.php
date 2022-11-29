<?php

namespace MyApplication\core;

/*
	Служебный класс для разбора входящего запроса
*/
class MyApplicationRequest {
	
	public static function isGet() {
		return ($_SERVER['REQUEST_METHOD'] === 'GET');
	}
	
	public static function isPost() {
		return ($_SERVER['REQUEST_METHOD'] === 'POST');
	}
	
	public static function isAjax(ickomi\IcKomiRequest $request = NULL) {
		$httpxRequestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'];
		if ($httpxRequestedWith == NULL)
			$httpxRequestedWith = '';
		return 'xmlhttprequest' == $httpxRequestedWith;
	}
	
	private static function fillVariables() {
		if (self::$__ickomi_request_array !== null)
			return;
		
		self::$__ickomi_getrequest_array = $_GET;
		self::$__ickomi_postrequest_array = $_POST;

		if (self::isGet()) 
			self::$__ickomi_request_array = self::$__ickomi_getrequest_array;
		else if (self::isPost())
			self::$__ickomi_request_array = self::$__ickomi_postrequest_array;
	}
	
	private static function Initialize() {
		self::fillVariables();
	} 
	
	public static function toArray() {
		if (self::$__ickomi_request_array === null)
			self::Initialize();
		
		return self::$__ickomi_request_array;
	}

	/*
		представление объекта в виде массива вида
		<параметр GET-запроса> => <значение GET-запроса>
	*/
	private static $__ickomi_getrequest_array;
	private static $__ickomi_postrequest_array;
	private static $__ickomi_request_array;
	
	/*
		Метод, получающий данные из GET-запроса
		
		$parameter - имя параметра в строке запроса.
		$defaultReturn - значение по умолчанию.
		
		Если $parameter не указан или равен NULL,
		то возвращается ассоциативный массив элементов вида:
			<параметр GET-запроса> => <его значение>		
			
		Если указан $parameter, но не $defaultReturn или он равен NULL, то возвращается:
			если параметр был в запросе - его значение
			если параметра не было запросе - значение $defaultReturn	
	*/
	public static function get($parameter = NULL, $defaultReturn = NULL) {
		self::Initialize();
		return self::provideRequestData(self::$__ickomi_getrequest_array, $parameter, $defaultReturn);
	}
	
	
	public static function post($parameter = NULL, $defaultReturn = NULL) {
		self::Initialize();
		
		return self::provideRequestData(self::$__ickomi_postrequest_array, $parameter, $defaultReturn);
	}
	
	private static function provideRequestData($arraySource, $parameterName = NULL, $defaultReturn = NULL) {
		if (is_null($parameterName) && is_null($defaultReturn)) {
			return $arraySource;
		}

		if (is_string($parameterName)) {
			if ($defaultReturn !== NULL && !array_key_exists($parameterName, $arraySource))
				return $defaultReturn;
			else
				return $arraySource[$parameterName];
		} 
	}
	
}