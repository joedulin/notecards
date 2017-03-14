<?php

class BaseMaEndpoints extends BaseMa911 {
	public $id = null;
	public $user_id = null;
	public $group_id = null;
	public $endpoint_type = 'sms';
	public $endpoint_value = null;
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'ma_endpoints';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'user_id',
			'group_id',
			'endpoint_type',
			'endpoint_value',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(
			'users' => array( 'my_column' => 'user_id', 'their_column' => 'id' ),
			'ma_groups' => array( 'my_column' => 'group_id', 'their_column' => 'id' )
		);
	}
}
