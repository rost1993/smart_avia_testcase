<?php

namespace MyApplication\core;

use MyApplication\MyApp;

/*
	Copyright: Rostislav Gashin (rost1993)
*/
class MyApplicationRoute {

	const GET = 1; 
	const POST = 2;

	public $route = '';
	public $type = '';
	public $controller = '';
	public $action = '';

	/*
	*/
	function __construct($route, $type = MyApplicationRoute::GET) {
		$this->type = empty($type) ? MyApplicationRoute::GET : $type;

		if($type == MyApplicationRoute::POST) {
			$this->route = $route;
		} else {
			$this->route = $route;
		}

		$this->load();
	}

	/*
	*/
	public static function get($route = '') {
		return new MyApplicationRoute($route, MyApplicationRoute::GET);
	}

	/*
	*/
	public static function post($route = '') {
		return new MyApplicationRoute($route, MyApplicationRoute::POST);
	}

	/*
	*/
	public function action($action = '') {
		$action_explode = explode('@', $action);

		$this->controller = empty($action_explode[0]) ? '' : $action_explode[0];
		$this->action = empty($action_explode[1]) ? '' : $action_explode[1];

		$this->load();

		return $this;
	}

	/*
	*/
	private function load() {
		if ($this->type == MyApplicationRoute::GET) {
			$this->update(MyApp::$routes['GET']);
		} else {
			$this->update(MyApp::$routes['POST']);
		}
	}


	/*
	*/
	private function update(&$routes) {
		$routes[$this->route] = [
			'controller' => $this->controller,
			'action' => $this->action,
		];
	}
}
