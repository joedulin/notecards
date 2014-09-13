<?php

class Login extends BaseController {
	public function __construct($f3, $params=array()) {
		parent::__construct($f3, $params, false);
	}

	public function login() {
		$this->pageRender('login/login', false);
	}

	public function loginAjax() {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$user = Users::login($username, $password);
		if (!$user) {
			$this->jsonError('Invalid username or password');
			return false;
		}
		$_SESSION['user'] = $user->extract_data();
		$this->jsonSuccess();
	}

	public function logout() {
		$_SESSION = array();
		session_destroy();
		session_start();
		$this->f3->reroute('/login');
	}

}
