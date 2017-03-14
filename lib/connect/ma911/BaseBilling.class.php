<?php

class BaseBilling extends BaseMa911 {
	public $id = null;
	public $user_id = null;
	public $transaction_type = 'debit_nrc';
	public $transaction_description = null;
	public $amount = null;
	public $create_dt = null;
	public $modify_dt = null;


	public function __construct($opts=array()) {
		parent::__construct($opts);
		$this->table = 'billing';
		$this->primary_key = 'id';
		$this->columns = array(
			'id',
			'user_id',
			'transaction_type',
			'transaction_description',
			'amount',
			'create_dt',
			'modify_dt',
		);
		$this->foreign_keys = array(
			'users' => array( 'my_column' => 'user_id', 'their_column' => 'id' )
		);
	}
}
