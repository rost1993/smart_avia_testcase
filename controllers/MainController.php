<?php

namespace controllers;

use MyApplication\MyApp;
use MyApplication\core\MyApplicationController;

/*
	Copyright: Rostislav Gashin (rost1993)
*/
class MainController extends MyApplicationController {

	/*
		Route: /
	*/
	public function index() {
		/*print_r(MyApp::$routes);
		exit;*/
		$this->render('my_application/index', []);	
	}

	/*
		Route: /generate_key
	*/
	public function generate() {
		require_once('../components/btcaddr.php');
	}
}