<?php

class Monkeys extends MysqlConnect {
	public $name;
	public $poops_given;

	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'monkeys';
		$this->columns = array( 'id', 'name', 'poops_given', 'create_dt', 'modify_dt' );
	}
}
