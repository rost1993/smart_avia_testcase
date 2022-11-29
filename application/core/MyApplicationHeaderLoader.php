<?php

namespace MyApplication\core;

use MyApplication\MyApp;

/*
	Класс для загрузки CSS и JS скриптов
	Copyright: Rostislav Gashin (rost1993)
*/
class MyApplicationHeaderLoader {

	/*
		Загрузка JS и CSS скриптов из папки
	*/
	public static function getHeader() {
		self::getCSS();
		self::getJS();
	}

	/*
		Загрузка CSS-скриптов
	*/
	private static function getCss() {
		foreach (MyApp::$app->css as $css) {
			$path_to_css = "css/" . $css;
			if(file_exists($path_to_css)) {
				$src = '/' . $path_to_css . '?ver=' . md5_file($path_to_css);
				echo "<link rel='stylesheet' href='" . $src . "'>";
			}
		}
	}

	/*
		Загрузка JS-скриптов
	*/
	private static function getJS() {
		foreach (MyApp::$app->js as $script) {
			$path_to_javascript = "js/" . $script;
			if(file_exists($path_to_javascript)) {
				$src = '/' . $path_to_javascript . '?ver=' . md5_file($path_to_javascript);
				echo "<script src='" . $src . "'></script>";
			}
		}
	}

	/*
		Общее название сайта
	*/
	public static function getTitle() {
		return (empty(MyApp::$app->title)) ? MyApp::$app->defaultTitle : MyApp::$app->title;
	}

	/*
		Получение favicon сайта
	*/
	public static function getFavicon() {
		if(empty(MyApp::$app->favicon))
			return "data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=";

		if(!file_exists('assets/favicon/' . MyApp::$app->favicon))
			return "data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=";

		return '/assets/favicon/' . MyApp::$app->favicon;
	}
}