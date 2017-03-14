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
		$this->pageRender('login/forgot', false);
	}

	//ajax -----------------------------------------------------------------------
	public function signup() {
		$username = $this->required('username');
		$password = $this->required('password');
		$email = $this->required('email');
		$phone = $this->required('phone');
		$phone = preg_replace('/[^0-9]/', '', $phone);
		$phone = (substr($phone, 0, 1) == '1') ? substr($phone, 1) : $phone;

		$esplit = explode('@', $email);
		if (count($esplit) != 2) {
			return $this->res(400, 'error', 'Invalid email address');
		}
		if (count(explode('.', $esplit[1])) != 2) {
			return $this->res(400, 'error', 'Invalid email address');
		}
		if (strlen($phone) != 10) {
			return $this->res(400, 'error', 'Please provide a vaild US/CAN phone number');
		}
		

		$user = Users::select('username', $username);
		if (!empty($user)) {
			return $this->res(400, 'error', 'Username is not available');
		}

		$user = new Users();
		$user->username = $username;
		$user->password = $this->hash_pass($password);
		$user->email = $email;
		$user->phone_number = $phone;
		if ($user->save()) {
			$rates = new Rates();
			$rates->user_id = $user->id;
			$rates->save();

			if (SIGNUP_CREDIT > 0) {
				$billing = new Billing();
				$billing->user_id = $user->id;
				$billing->transaction_type = 'credit_promo';
				$billing->transaction_description = 'Signup Credit. Enjoy :-)';
				$billing->amount = SIGNUP_CREDIT;
				$billing->save();
			}
			return $this->authenticate();
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
