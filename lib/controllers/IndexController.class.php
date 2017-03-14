<?php

class IndexController extends BaseController {
	public function __construct($f3=array(), $params=array()) {
		parent::__construct($f3, $params);
	}

	public function landing_page() {
		$this->pageRender('index/landing');
	}
}
