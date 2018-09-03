<?php

namespace Micro\Controller;

use \Micro\Core\Controller as Controller;

class Index extends Controller {
	public function index()
	{
		$this->loadModel('Main');
		
		$this->setView($this->view);
	}
}
