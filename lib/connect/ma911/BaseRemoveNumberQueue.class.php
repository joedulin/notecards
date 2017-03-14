<?php

class BaseRemoveNumberQueue extends BaseMa911 {
	public $id = null;
	public $user_id = null;
	public $did_id = null;
	public $process_status = 'new';
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'remove_number_queue';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'user_id',
			'did_id',
			'process_status',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(
			'users' => array( 'my_column' => 'user_id', 'their_column' => 'id' ),
			'dids' => array( 'my_column' => 'did_id', 'their_column' => 'id' )
		);
	}
}
