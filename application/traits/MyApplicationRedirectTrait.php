<?php

namespace IcKomi\core;

/*
	Служебный trait, содержащий вспомогательные служебные функции, которые используются при редиректе на страницы с ошибками

	Copyright: Rostislav Gashin, rost1993
*/
trait IcKomiRedirectTrait {

	public static function redirectHomePage() {
		header('Location: /');
		exit;
	}

	/*
		Функция редиректа на страницу 'Not found'
	*/
	public static function redirectNotFoundPage() {
		$not_found_page = '';

		// Определяем если пользователь запрашивает файл (запрос содержит в себе uploads-file/) и он не найден то тогда редиректим на осубую страницу
		/*if(preg_match('/uploads-file\//ui', $_SERVER['REQUEST_URI']) == 1) {
			$not_found_page = (array_key_exists('404_FILE', \IcKomiApp::$app->defaultErrorPage))
				? '../views/layouts/errors/' . \IcKomiApp::$app->defaultErrorPage['404_FILE'] . '.php'
				: '/';
		} else {*/
			$not_found_page = (array_key_exists(404, \IcKomiApp::$app->defaultErrorPage))
				? '../views/layouts/errors/' . \IcKomiApp::$app->defaultErrorPage[404] . '.php'
				: '/';
		//}

		$code = 404;

		http_response_code($code);
		require_once($not_found_page);
		exit;
	}

	/*
		Функция редиректа на страницу 'Nor found' если не найден файл.
		Используем специальный шаблон в ктором нет футеров. Он нужен для отрисовки внутри каких-нибдь фреймов например.
	*/
	public static function redirectNotFoundFile() {
		$not_found_page = (array_key_exists('404_FILE', \IcKomiApp::$app->defaultErrorPage))
			? '../views/layouts/errors/' . \IcKomiApp::$app->defaultErrorPage['404_FILE'] . '.php'
			: '/';

		$code = 404;

		http_response_code($code);
		require_once($not_found_page);
		exit;
	}

	/*
		Функция редиректа на страницу 'Access denied'
	*/
	public static function redirectAccessDenied() {
		$error_page = (array_key_exists(403, \IcKomiApp::$app->defaultErrorPage))
			? '../views/layouts/errors/' . \IcKomiApp::$app->defaultErrorPage[403] . '.php'
			: '/';

		$code = 403;

		http_response_code($code);
		require_once($error_page);
		exit;
	}

	/*
		Функция редиректа на страницу 'Access denied'
		Используем специальный шаблон в ктором нет футеров. Он нужен для отрисовки внутри каких-нибдь фреймов например.
	*/
	public static function redirectAccessDeniedFile() {
		$error_page = (array_key_exists('403_FILE', \IcKomiApp::$app->defaultErrorPage))
			? '../views/layouts/errors/' . \IcKomiApp::$app->defaultErrorPage['403_FILE'] . '.php'
			: '/';

		$code = 403;

		http_response_code($code);
		require_once($error_page);
		exit;
	}
}
