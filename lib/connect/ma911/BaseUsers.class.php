<?php

class BaseUsers extends BaseMa911 {
	public $id = null;
	public $master_id = null;
	public $reseller_id = null;
	public $user_type = 'master';
	public $username = null;
	public $password = null;
	public $email = null;
	public $phone_number = null;
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'users';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'master_id',
			'reseller_id',
			'user_type',
			'username',
			'password',
			'email',
			'phone_number',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(

		);
	}
}
