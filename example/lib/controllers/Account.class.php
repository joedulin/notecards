<?php

class Account extends BaseController {
	public function __construct($f3, $params=array()) {
		parent::__construct($f3, $params);
	}

	public function change_password() {
		$this->pageRender('account/changepass');
	}

	public function change_password_ajax() {
		$curpass = $_POST['curpass'];
		$password = $_POST['password'];
		$user = Users::login($_SESSION['user']->username, $curpass);
		if ($user) {
			if ($user->setPassword($password)) {
				$this->jsonSuccess();
			} else {
				$this->jsonError();
			}
		} else {
			$this->jsonError();
		}
	}
}
