<?php

class LoginController extends BaseController {
	public function __construct($f3=array(), $params=array()) {
		parent::__construct($f3, $params, false);
	}

	public function login_page() {
		$_SESSION = array();
		session_destroy();
		session_start();
		$this->pageRender('login/login', false);
	}

	public function signup_page() {
		$this->pageRender('login/signup', false);
	}

	public function forgotpass_page() {
		echo 'Well... you shouldn\'t have done that. Seems like a bad idea :-/';
	}

	//ajax -----------------------------------------------------------------------
	public function signup() {
		$username = $this->required('username');
		$password = $this->required('password');

		$user = Users::select('username', $username);
		if (!empty($user)) {
			return $this->res(400, 'error', 'Username is not available');
		}

		$user = new Users();
		$user->username = $username;
		$user->password = $this->hash_pass($password);
		if ($user->save()) {
			return $this->res(200, 'success', $user->clean());
		}
		return $this->res(500, 'error', 'There was an error creating the user');
	}

	public function authenticate() {
		$username = $this->required('username');
		$password = $this->required('password');
		$password = $this->hash_pass($password);

		$user = Users::select(array(
			'username' => $username,
			'password' => $password
		));
		if (empty($user)) {
			return $this->res(400, 'error', 'Invalid username/password combination');
		}
		$user = $user[0];
		$_SESSION['user'] = $user;
		return $this->res(200, 'success', 'Success');
	}
}
