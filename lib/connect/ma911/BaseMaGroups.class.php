<?php

class BaseMaGroups extends BaseMa911 {
	public $id = null;
	public $user_id = null;
	public $group_name = null;
	public $e_name = null;
	public $e_address = null;
	public $e_unit_type = 'none';
	public $e_unit_number = 'none';
	public $e_city = null;
	public $e_state = null;
	public $e_zip = null;
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'ma_groups';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'user_id',
			'group_name',
			'e_name',
			'e_address',
			'e_unit_type',
			'e_unit_number',
			'e_city',
			'e_state',
			'e_zip',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(
			'users' => array( 'my_column' => 'user_id', 'their_column' => 'id' )
		);
	}
}
