<?php

class BaseLoadNumbersQueue extends BaseMa911 {
	public $id = null;
	public $user_id = null;
	public $group_id = null;
	public $processing_status = 'new';
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'load_numbers_queue';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'user_id',
			'group_id',
			'processing_status',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(
			'users' => array( 'my_column' => 'user_id', 'their_column' => 'id' ),
			'ma_groups' => array( 'my_column' => 'group_id', 'their_column' => 'id' )
		);
	}
}
