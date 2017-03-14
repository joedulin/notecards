<?php

class BaseMa911 extends BaseMysql {
	public function __construct($opts=array()) {
		$connect = array(
			'master' => array(
				'hostname' => 'localhost',
				'database' => 'ma911',
				'user' => 'portal_user',
				'password' => 'water is best from styrofoam cups',
				'port' => '3306'
			),
			'slave' => array(
				'hostname' => 'localhost',
				'database' => 'ma911',
				'user' => 'portal_user',
				'password' => 'water is best from styrofoam cups',
				'port' => '3306'
			)
		);
		parent::__construct($opts, $connect);
	}
}
