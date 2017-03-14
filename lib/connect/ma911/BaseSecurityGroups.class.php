<?php

class BaseSecurityGroups extends BaseMa911 {
	public $id = null;
	public $group_name = null;
	public $group_description = null;
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'security_groups';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'group_name',
			'group_description',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(

		);
	}
}
