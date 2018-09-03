<?php

namespace Micro\Core;

class App {
	private $controller;
	private $function;

	public function init()
	{
		$controller = Router::getController();
		$function = Router::getControllerFunction();

		Router::loadController($controller, $function);

		return $this;
	}

	public function debug()
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
}