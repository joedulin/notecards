<?php

class BaseRates extends BaseMa911 {
	public $id = null;
	public $user_id = null;
	public $did_nrc = '0.350000';
	public $did_mrc = '0.350000';
	public $ecrc_call = '90.000000';
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'rates';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'user_id',
			'did_nrc',
			'did_mrc',
			'ecrc_call',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(
			'users' => array( 'my_column' => 'user_id', 'their_column' => 'id' )
		);
	}
}
