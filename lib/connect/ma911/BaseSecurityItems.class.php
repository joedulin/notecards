<?php

class BaseSecurityItems extends BaseMa911 {
	public $id = null;
	public $item_name = null;
	public $item_description = null;
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'security_items';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'item_name',
			'item_description',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(

		);
	}
}
