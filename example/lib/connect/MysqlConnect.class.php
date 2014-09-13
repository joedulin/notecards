<?php

class MysqlConnect extends BaseConnect {
	public $_master;
	public $_slave;
	public $memcache;
	public $cache;

	public $table;
	public $columns;
	
	public $id;
	public $create_dt;
	public $modify_dt;

	private function _myConfigs() {
		$this->configs = (object) array(
			'mysql' => (object) array(
				//Master database
				'db_master_host' => 'localhost',
				'db_master_dbname' => 'example',
 				'db_master_user' => 'exampleUser',
 				'db_master_pass' => 'examplePassword',

				//Slave database
				'db_slave_host' => 'localhost',
				'db_slave_dbname' => 'example',
				'db_slave_user' => 'exampleUser',
				'db_slave_pass' => 'examplePassword'
			)
		);
	}

	public function __construct($opts=array()) {
		parent::__construct();
		$this->_myConfigs();
		$this->_master = new PDO(
			sprintf('mysql:host=%s;dbname=%s', 
				$this->configs->mysql->db_master_host, 
				$this->configs->mysql->db_master_dbname
			),
			$this->configs->mysql->db_master_user,
			$this->configs->mysql->db_master_pass,
			array( PDO::ATTR_PERSISTENT => true )
		);
		$this->_slave = new PDO(
			sprintf('mysql:host=%s;dbname=%s', 
				$this->configs->mysql->db_slave_host,
				$this->configs->mysql->db_slave_dbname
			),
			$this->configs->mysql->db_slave_user,
			$this->configs->mysql->db_slave_pass,
			array( PDO::ATTR_PERSISTENT => true )
		);
		//$this->memcache = new MemcacheConnect();
		//$this->cache = false;
		if (is_array($opts) || is_object($opts)) {
			foreach ($opts as $k => $v) {
				$this->$k = $v;
			}
		}
	}

	public function prepInsert() {
		if (!empty($this->id)) {
			return false;
		}
		$values = array();
		$qmarks = array();
		foreach ($this->columns as $i => $column) {
			$values[$i] = $this->$column;
			$qmarks[] = '?';
		}
		$q = sprintf("INSERT INTO %s (%s) VALUES (%s)",
			$this->table,
			implode(', ', $this->columns),
			implode(', ', $qmarks)
		);
		$statement = $this->_master->prepare($q);
		if ($statement->execute($values)) {
			/*
			$key = sprintf('%s%s%s', 
				$this->configs->mysql->db_master_dbname,
				$this->table,
				$this->_master->lastInsertId()
			);
			$this->memcache->wrap($key, $this->extract_data);
			*/
			$this->getById($this->_master->lastInsertId());
			return true;
		} else {
			$errors = $statement->errorInfo();
			error_log(print_r($errors, true));
			error_log(sprintf('SQLSTATE: %s', $errors[0]));
			error_log(sprintf('MySQL Error COde: %s', $error[1]));
			error_log(sprintf('MySQL Message: %s', $error[2]));
			return false;
		}
	}

	public function prepUpdate() {
		if (empty($this->id)) {
			return false;
		}
		$values = array();
		$sets = array();
		foreach ($this->columns as $i => $column) {
			if ($column != 'create_dt' && $column != 'modify_dt') {
				$values[$i] = $this->$column;
				$sets[] = sprintf("%s = ?", $column);
			}
		}
		$q = sprintf("UPDATE %s SET %s WHERE id = ?", 
			$this->table,
			implode(', ', $sets)
		);
		$values[] = $this->id;
		$statement = $this->_master->prepare($q);
		if ($statement->execute($values)) {
			/*
			$key = sprintf('%s%s%s',
				$this->configs->mysql->db_master_dbname,
				$this->table,
				$this->id
			);
			$this->memcache->wrap($key, $this->extract_data);
			*/
			return true;
		} else {
			$errors = $statement->errorInfo();
			error_log(sprintf('SQLSTATE: %s', $errors[0]));
			error_log(sprintf('MySQL Error COde: %s', $error[1]));
			error_log(sprintf('MySQL Message: %s', $error[2]));
			return false;
		}
	}

	public function save() {
		return (empty($this->id)) ? $this->prepInsert() : $this->prepUpdate();
	}



	public function prepQuery($filters=array(), $orderby=false, $direction='', $limit=false, $cache=false) {
		$q = sprintf("SELECT * FROM %s", $this->table);
		$values = array();
		foreach ($filters as $k => $v) {
			if (!in_array($k, $this->columns)) {
				unset($filters[$k]);
				continue;
			}
			$operator = '=';
			if (is_array($v)) {
				$operator = $v['operator'];
				$v = $v['value'];
			}
			if (strpos($q, 'WHERE') === false) {
				$q = sprintf("%s WHERE %s %s ?", $q, $k, $operator);
			} else {
				$q = sprintf("%s AND %s %s ?", $q, $k, $operator);
			}
			$values[] = $v;
		}
		if ($orderby) {
			if (!in_array($orderby, $this->columns)) {
				return false;
			}
			$q = sprintf("%s ORDER BY %s %s", $q, $orderby, $direction);
		}
		if ($limit) {
			list($offset, $number) = explode(',', $limit);
			if (!is_numeric($offset) || !is_numeric($number)) {
				return false;
			}
			$q = sprintf("%s LIMIT %s,%s", $q, $offset, $number);
		}
		/*
		$key = sprintf('%s%s%s',
			$this->configs->mysql->db_master_dbname,
			$q,
			serialize($values)
		);
		$ret = $this->memcache->unwrap($key);
		if ($ret) {
			$class = get_called_class();
			$ret_new = array();
			foreach ($ret as $i => $vals) {
				$ret_new[] = new $class($vals);
			}
			return $ret_new;
		}
		*/
		$statement = $this->_slave->prepare($q);
		if ($statement->execute($values)) {
			$ret = $statement->fetchAll(PDO::FETCH_CLASS, get_called_class());
			/*
			$vals = array();
			foreach ($ret as $item) {
				$vals[] = $item->extract_data();
			}
			$this->memcache->wrap($key, $vals);
			*/
			return $ret;
		}
		$errors = $statement->errorInfo();
		error_log(sprintf('SQLSTATE: %s', $errors[0]));
		error_log(sprintf('MySQL Error COde: %s', $error[1]));
		error_log(sprintf('MySQL Message: %s', $error[2]));
		return array();
	}

	public function get($id, $cache=false) {
		$key = sprintf('%s%s%s',
			$this->configs->mysql->db_master_dbname,
			$this->table,
			$id
		);
		$self = $this->prepQuery(array('id' => $id), false, '', false, $cache);
		if (count($self) > 0) {
			$self = $self[0];
			foreach ($self as $k => $v) {
				$this->$k = $v;
			}
			return true;
		}
		return false;
	}

	public function deleteRecord() {
		if (empty($this->id)) {
			return false;
		}
		$q = sprintf("DELETE FROM %s WHERE id = ?", $this->table);
		$values = array( $this->id );
		$statement = $this->_master->prepare($q);
		if ($statement->execute($values)) {
			/*
			$key = sprintf('%s%s%s',
				$this->configs->mysql->db_master_dbname,
				$this->table,
				$this->id
			);
			$this->memcache->delete($key);
			*/
			foreach ($this->columns as $column) {
				$this->$column = null;
			}
			return true;	
		}
		$errors = $statement->errorInfo();
		error_log(sprintf('SQLSTATE: %s', $errors[0]));
		error_log(sprintf('MySQL Error COde: %s', $error[1]));
		error_log(sprintf('MySQL Message: %s', $error[2]));
		return false;	
	}

	public function extract_data() {
		$values = array();
		foreach ($this->columns as $column) {
			$values[$column] = $this->$column;
		}
		return (object) $values;
	}

	//Static function ----------------------------------------------------------

	static function getById($id, $cache=false) {
		$class = get_called_class();
		$self = new $class();
		$self->get($id);
		return $self;
	}

	static function getByColumn($column, $value, $orderby=false, $direction='', $limit=false, $cache=false) {
		$class = get_called_class();
		$self = new $class();
		return $self->prepQuery(array($column => $value), $orderby, $direction, $limit, $cache);
	}

	static function getByMulti($filters=array(), $orderby=false, $direction='', $limit=false, $cache=false) {
		$class = get_called_class();
		$self = new $class();
		return $self->prepQuery($filters, $orderby, $direction, $limit, $cache);
	}

	static function getAll($orderby=false, $direction='', $limit=false, $cache=false) {
		$class = get_called_class();
		$self = new $class();
		return $self->prepQuery(array(), $orderby, $direction, $limit, $cache);
	}

	static function deleteById($id) {
		$class = get_called_class();
		$self = new $class();
		$self->id = $id;
		return $self->deleteRecord();
	}

	static function rawQuery($q, $values=false, $conn='slave', $cache=false) {
		$class = get_called_class();
		$self = new $class();

		$conn = ($conn == 'slave') ? $self->_slave : $self->_master;
		$statement = $conn->prepare($q);
		if (is_array($values)) {
			$success = $statement->execute($values);
		} else {
			$success = $statement->execute();
		}
		if ($success) {
			return $statement->fetchAll(PDO::FETCH_OBJ);
		}
		$errors = $statement->errorInfo();
		error_log(sprintf('SQLSTATE: %s', $errors[0]));
		error_log(sprintf('MySQL Error COde: %s', $error[1]));
		error_log(sprintf('MySQL Message: %s', $error[2]));
		return array();
	}
}
