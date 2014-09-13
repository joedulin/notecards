<?php

class Test extends BaseController {
	public function __construct($f3, $params=array()) {
		parent::__construct($f3, $params, false);
	}

	public function session() {
		header('Content-type: application/json');
		echo json_encode($_SESSION);
	}
}
