<?php

class BaseDids extends BaseMa911 {
	public $id = null;
	public $user_id = null;
	public $group_id = null;
	public $number = null;
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'dids';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'user_id',
			'group_id',
			'number',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(
			'ma_groups' => array( 'my_column' => 'group_id', 'their_column' => 'id' ),
			'users' => array( 'my_column' => 'user_id', 'their_column' => 'id' )
		);
	}
}
