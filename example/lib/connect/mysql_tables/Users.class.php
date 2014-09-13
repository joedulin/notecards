<?php

class Users extends MysqlConnect {
	public $username;
	public $password;

	private $_salt = 'this is a user salt and stuff'; //Also defined in static login function

	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'users';
		$this->columns = array( 'id', 'username', 'password', 'create_dt', 'modify_dt' );
	}

	public function setPassword($pass) {
		if (empty($this->id)) {
			return false;
		}
		$this->password = $this->_hashPass($pass);
		if ($this->save()) {
			return $this->get($this->id);
		}
		return false;
	}

	public function checkPass($pass) {
		$pass = $this->_hashPass($pass);
		return ($this->password == $pass);
	}

	private function _hashPass($pass) {
		return hash('sha256', sprintf('%s%s', $this->_salt, $pass));
	}

	static function login($username, $pass) {
		$pass_check = hash('sha256', sprintf('%s%s', 'this is a user salt and stuff', $pass));
		$user = Users::getByMulti(array('username' => $username, 'password' => $pass_check));
		if (count($user) == 0) {
			return false;
		} else {
			return $user[0];
		}
	}
}
