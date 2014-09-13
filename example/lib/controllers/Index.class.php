<?php

class Index extends BaseController {
	public function __construct($f3, $params=array()) {
		parent::__construct($f3, $params);
	}

	public function landing() {
		$this->pageRender('index/landing');
	}
}
