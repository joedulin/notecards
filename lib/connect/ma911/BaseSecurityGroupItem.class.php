<?php

class BaseSecurityGroupItem extends BaseMa911 {
	public $id = null;
	public $group_id = null;
	public $item_id = null;
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'security_group_item';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'group_id',
			'item_id',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(
			'security_groups' => array( 'my_column' => 'group_id', 'their_column' => 'id' ),
			'security_items' => array( 'my_column' => 'item_id', 'their_column' => 'id' )
		);
	}
}
