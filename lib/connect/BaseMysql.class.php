<?php

class BaseMysql extends BaseConnect {
	private $_master;
	private $_slave;
	public $table;
	public $columns = array();
	public $primary_key;
	public $foreign_keys = array();
	public $joins;
	public $user_record;

	public function __construct($table_object = array(), $connect=false) {
		$master = ($connect) ? $connect['master'] : false;
		$slave = ($connect) ? $connect['slave'] : false;
		$this->_connect($master, $slave);
		foreach ($this->columns as $k) {
			$this->$k = null;
		}
		foreach ($table_object as $k => $v) {
			$this->$k = $v;
		}
		$this->joins = new stdClass();
		$this->user_record = null;
	}

	private function _connect($master=false, $slave=false) {
		$master = ($master) ? $master : array(
			'hostname' => 'master_db',
			'database' => 'my_database',
			'user' => 'master_user',
			'password' => 'master_password',
			'port' => '3306'
		);
		$slave = ($slave) ? $slave : array(
			'hostname' => 'slave_db',
			'database' => 'my_database',
			'user' => 'slave_user',
			'password' => 'slave_password',
			'port' => '3306'
		);
		$master_connect = sprintf('mysql:dbname=%s;host=%s;port=%s', $master['database'], $master['hostname'], $master['port']);
		$slave_connect = sprintf('mysql:dbname=%s;host=%s;port=%s', $slave['database'], $slave['hostname'], $slave['port']);
		try {
			$this->_master = new PDO($master_connect, $master['user'], $master['password'], array(
				PDO::ATTR_PERSISTENT => true
			));
		} catch (PDOException $e) {
			echo 'Master connection failed: ' . $e->getMessage();
			exit;
		}
		try {
			$this->_slave = new PDO($slave_connect, $slave['user'], $slave['password'], array(
				PDO::ATTR_PERSISTENT => true
			));
		} catch (PDOException $e) {
			echo 'Slave connection failed: ' . $e->getMessage();
		}
	}

	public function prepQuery($q, $values=array()) {
		//error_log($q);
		//error_log(print_r($values, true));
		$statement = $this->_slave->prepare($q);
		$statement->setFetchMode(PDO::FETCH_OBJ);
		$executed = false;
		if (!empty($values)) {
			$executed = $statement->execute($values);
		} else {
			$executed = $statement->execute();
		}
		if ($executed) {
			return $statement->fetchAll();
		} else {
			$errors = $statement->errorInfo();
			error_log(sprintf('SQLSTATE: %s', $errors[0]));
			error_log(sprintf('MySQL Error Code: %s', $errors[1]));
			error_log(sprintf('MySQL Message: %s', $errors[2]));
			return array();
		}
	}

	public function buildQuery($obj=false, $count=false) {
		if ($count) {
			$query = sprintf("SELECT count(*) AS count FROM `%s`", $this->table);
		} else {
			$query = sprintf("SELECT * FROM `%s`", $this->table);
		}
		$distinct = false;
		$orderby = false;
		$direction = 'ASC';
		$limit = false;
		$offset = false;
		$search = false;
		$orvalues = array();
		$where = false;
		$values = array();

		if (is_array($obj) || is_object($obj)) {
			if (is_array($obj)) {
				$obj = (object) $obj;
			}

			if (isset($obj->distinct)) {
				if (in_array($obj->distinct, $this->columns)) {
					$distinct = $obj->distinct;
					$query = sprintf("SELECT DISTINCT `%s` FROM `%s`", $obj->distinct, $this->table);
					unset($obj->distinct);
				}
			}

			if (isset($obj->orderby)) {
				if (in_array($obj->orderby, $this->columns)) {
					$direction = (isset($obj->direction) && $obj->direction == 'DESC') ? 'DESC' : 'ASC';
					$orderby = sprintf('ORDER BY %s %s', $obj->orderby, $direction);
					unset($obj->orderby);
					if (isset($obj->direction)) {
						unset($obj->direction);
					}
				}
			}
			if (isset($obj->limit)) {
				$limit = preg_replace('/[^0-9]/', '', $obj->limit);
				$limit = ($limit) ? sprintf('LIMIT %s', $limit) : false;
				unset($obj->limit);
			}
			if (isset($obj->offset)) {
				$offset = preg_replace('/[^0-9]/', '', $obj->offset);
				$offset = ($offset) ? sprintf('OFFSET %s', $offset) : false;
				unset($obj->offset);
			}
			if (isset($obj->search)) {
				$search = array();
				foreach ($obj->search as $column => $value) {
					$search[] = sprintf('%s LIKE ?', $column);
					$orvalues[] = sprintf('%%%s%%', $value);
				}
			}
			foreach ($obj as $column => $value) {
				if (is_array($value) || is_object($value)) {
					$value = (is_array($value)) ? (object) $value : $value;
				}
				if (!in_array($column, $this->columns)) {
					if (!is_object($value) || !isset($value->force)) {
						continue;
					}
				}
				if (is_array($value) || is_object($value)) {
					if (!in_array(strtolower($value->operator), array('=', '!=', '>', '<', '>=', '<=', 'like', 'is', 'is not', 'between'))) {
						continue;
					}
					if ($value->operator == 'between') {
						$string = sprintf('%s BETWEEN ? AND ?', $column);
						$values[] = $value->value1;
						$values[] = $value->value2;
					} else {
						$string = sprintf('%s %s ?', $column, $value->operator);
						$values[] = $value->value;
					}
					$where = ($where) ? sprintf('%s AND %s', $where, $string) : sprintf('WHERE %s', $string);
				} else {
					if (!$where) {
						$where = sprintf('WHERE %s = ?', $column);
					} else {
						$where = sprintf('%s AND `%s` = ?', $where, $column);
					}
					$values[] = $value;
				}
			}
			$query = ($where) ? sprintf('%s %s', $query, $where) : $query;
			if ($search) {
				if ($where) {
					$query = sprintf('%s AND (%s)', $query, implode(' OR ', $search));
				} else {
					$query = sprintf('%s WHERE (%s)', $query, implode(' OR ', $search));
				}
				$values = array_merge($values, $orvalues);
			}
			$query = ($orderby) ? sprintf('%s %s', $query, $orderby) : $query;
			$query = ($limit) ? sprintf('%s %s', $query, $limit) : $query;
			$query = ($offset) ? sprintf('%s %s', $query, $offset) : $query;
			if (!$distinct) {
				return $this->prepQuery($query, $values);
			} else {
				$rows = $this->prepQuery($query, $values);
				$ret = array();
				foreach ($rows as $row) {
					$ret[] = $row->$distinct;
				}
				return $ret;
			}
		} else if ($obj === false) {
			return $this->prepQuery($query);
		} else {
			$query = sprintf('%s WHERE `%s` = ?', $query, $this->primary_key);
			return $this->prepQuery($query, array($obj));
		}
	}

	public function save($force_insert = false) {
		$ticked_columns = $this->columns;
		$table = sprintf('`%s`', $this->table);
		$pk = $this->primary_key;
		$type = '';
		if ($this->$pk && !$force_insert) {
			$values = array();
			foreach ($ticked_columns as $i => $column) {
				switch ($column) {
					case 'create_dt':
					case 'modify_dt':
						unset($ticked_columns[$i]);
						break;
					default:
						$ticked_columns[$i] = sprintf('`%s` = ?', $column);
						$values[] = $this->$column;
						break;
				}
			}
			$values[] = $this->$pk;
			$query = sprintf("UPDATE %s SET %s WHERE `%s` = ?", $table, implode(', ', $ticked_columns), $pk);
			$type = 'update';
		} else {
			$questions = array();
			$values = array();
			foreach ($ticked_columns as $i => $column) {
				$ticked_columns[$i] = sprintf('`%s`', $column);
				if (is_null($this->$column)) {
					$questions[] = 'NULL';
				} else {
					$questions[] = '?';
					$values[] = $this->$column;
				}
			}
			$query = sprintf("INSERT INTO %s (%s) VALUES (%s)", $table, implode(',', $ticked_columns), implode(',', $questions));
			$type = 'insert';
		}
		$statement = $this->_master->prepare($query);
		if ($statement->execute($values)) {
			if (!$this->$pk) {
				$this->$pk = $this->_master->lastInsertId();
			}
			$log = array(
				'type' => $type,
				'table' => $this->table,
				'data' => json_encode($this->clean())
			);
			if ($this->user_record && $this->user_record->web_hook_url) {
				$log['post_url'] = $this->user_record->web_hook_url;
			}
			$this->_send_hook($log);
			error_log(print_r($log, true));
			//$this->_notify_slack(json_encode($log));
			return true;
		} else {
			$errors = $statement->errorInfo();
			error_log(sprintf('SQLSTATE: %s', $errors[0]));
			error_log(sprintf('MySQL Error Code: %s', $errors[1]));
			error_log(sprintf('MySQL Message: %s', $errors[2]));
			$this->_notify_slack(json_encode($errors));
			return false;
		}
	}

	public function deleteRow() {
		$pk = $this->primary_key;
		if (!$this->$pk) {
			return true;
		}
		$table = sprintf('`%s`', $this->table);
		$pk_tick = sprintf('`%s`', $pk);
		$query = sprintf('DELETE FROM %s WHERE %s = ?', $table, $pk_tick);
		$statement = $this->_master->prepare($query);
		if ($statement->execute(array($this->$pk))) {
			$log = array(
				'type' => 'delete',
				'table' => $this->table,
				'data' => $pk
			);
			$this->_send_hook($log);
			return true;
		} else {
			$errors = $statement->errorInfo();
			error_log(sprintf('SQLSTATE: %s', $errors[0]));
			error_log(sprintf('MySQL Error Code: %s', $errors[1]));
			error_log(sprintf('MySQL Message: %s', $errors[2]));
			$this->_notify_slack(json_encode($errors));
			return false;
		}
	}

	public function deleteRecord() {
		return $this->deleteRow();
	}

	public function join($tables, $search_obj=array()) {
		$query = "SELECT";
		$ts = array();
		$first = true;
		foreach ($tables as $table) {
			$this->joins->$table = array();
			$classname = str_replace('_', ' ', $table);
			$classname = ucwords($classname);
			$classname = str_replace(' ', '', $classname);
			$t = new $classname();
			$ts[$t->table] = $t;
			foreach ($t->columns as $column) {
				if ($first) {
					$query = sprintf('%s `%s`.`%s` AS %s_%s', $query, $table, $column, $table, $column);
					$first = false;
				} else {
					$query = sprintf('%s,  `%s`.`%s` AS %s_%s', $query, $table, $column, $table, $column);
				}
			}
		}
		$query = sprintf('%s FROM `%s`', $query, $this->table);
		$previous = $this;
		foreach ($ts as $table) {
			$query = sprintf('%s JOIN `%s` ON `%s`.`%s` = `%s`.`%s`',
				$query,
				$table->table,
				$previous->table,
				(isset($table->foreign_keys[$previous->table])) ? $table->foreign_keys[$previous->table]['their_column'] : $previous->foreign_keys[$table->table]['my_column'],
				$table->table,
				(isset($table->foreign_keys[$previous->table])) ? $table->foreign_keys[$previous->table]['my_column'] : $previous->foreign_keys[$table->table]['their_column']
			);
			$previous = $table;
		}
		$ts[$this->table] = $this;
		$values = array();
		$orderby = false;
		$direction = 'ASC';
		$limit = false;
		$offset = false;
		$where = false;
		if (!empty($search_obj)) {
			if (is_object($search_obj)) {
				$search_obj = (array) $search_obj;
			}
			$obj = $search_obj;

			if (isset($obj['orderby'])) {
				if (in_array($obj['orderby'], $this->columns)) {
					$direction = (isset($obj['direction']) && strtoupper($obj['direction']) == 'DESC') ? 'DESC' : 'ASC';
					$orderby = sprintf('ORDER BY `%s` %s', $obj['orderby'], $direction);
				}
				unset($obj['orderby']);
				if (isset($obj['direction'])) {
					unset($obj['direction']);
				}
			}

			if (isset($obj['limit'])) {
				$limit = preg_replace('/[^0-9]/', '', $obj['limit']);
				$limit = (is_integer($limit)) ? sprintf('LIMIT %s', $limit) : false;
				unset($obj['limit']);
			}

			if (isset($obj['offset'])) {
				$offset = preg_replace('/[^0-9]/', '', $obj['offset']);
				$offset = (is_integer($offset)) ? sprintf('OFFSET %s', $offset) : false;
				unset($obj['offset']);
			}

			$questions = array();
			$values = array();
			foreach ($obj as $k => $v) {
				if (strpos('.', $k) === false) {
					continue;
				}
				list($table, $column) = explode('.', $k);
				if (!in_array($table, array_keys($ts))) {
					continue;
				}
				if (!in_array($column, $ts[$table]->columns)) {
					continue;
				}
				if (!$where) {
					$where = sprintf('`%s`.`%s` = ?', $table, $column);
				} else {
					$where = sprintf('%s AND `%s`.`%s` = ?', $where, $table, $column);
				}
				$values[] = $v;
			}
		}
		if ($where) {
			$query = sprintf('%s WHERE %s AND `%s`.`%s` = ?', $query, $where, $this->table, $this->primary_key);
			$values[] = $this->{$this->primary_key};
		} else {
			$query = sprintf('%s WHERE `%s`.`%s` = ?', $query, $this->table, $this->primary_key);
			$values[] = $this->{$this->primary_key};
		}
		if ($orderby) {
			$query = sprintf('%s %s', $query, $orderby);
		}
		if ($limit) {
			$query = sprintf('%s %s', $query, $limit);
		}
		if ($offset) {
			$query = sprintf('%s %s', $query, $offset);
		}
		$statement = $this->_slave->prepare($query);
		$statement->setFetchMode(PDO::FETCH_OBJ);
		if (!empty($values)) {
			$statement->execute($values);
		} else {
			$statement->execute();
		}
		$rows = $statement->fetchAll();
		$results = array();
		array_pop($ts);
		foreach ($rows as $row) {
			$r = array();
			foreach ($ts as $table => $tdata) {
				$this_row = (object) array();
				foreach ($tdata->columns as $column) {
					$name = sprintf('%s_%s', $table, $column);
					$this_row->$column = $row->$name;
				}
				$this->joins->{$table}[] = $this_row;
			}
			$results[] = $r;
		}
		return $results;
	}

	public function clean() {
		$obj = new stdClass();
		foreach ($this->columns as $column) {
			$obj->$column = $this->$column;
		}
		return $obj;
	}

	public function get_count($search=array()) {
		$q = sprintf("SELECT COUNT(*) as count FROM %s", $this->table);
		$where_string = false;
		$where_values = array();
		foreach ($search as $k => $v) {
			if (!is_array($v) && !is_object($v)) {
				$where_string = ($where_string) ? sprintf('%s AND %s = ', $where_string, $k) : sprintf(' WHERE %s = ', $k);
				if ($v === null) {
					$where_string = sprintf('%s null', $where_string);
				} else {
					$where_string = sprintf('%s ?', $where_string);
					$where_values[] = $v;
				}
			} else {
				$v = (object) $v;
				$line = sprintf('%s %s', $k, $v->operator);
				$where_string = ($where_string) ? sprintf('%s AND %s', $where_string, $line) : sprintf(' WHERE %s', $line);
				if ($v === null) {
					$where_string = sprintf('%s null', $where_string);
				} else {
					$where_string = sprintf('%s ?', $where_string);
					$where_values[] = $v->value;
				}
			}
		}
		$q = sprintf('%s%s', $q, $where_string);
		$ret = $this->prepQuery($q, $where_values);
		$ret = array_shift($ret);
		return $ret->count;
	}

	//Static Functions ----------------------------------------------------------

	static function query($query, $values=array()) {
		if (!is_array($values)) {
			$values = func_get_args();
			array_shift($values);
		}
		$class = get_called_class();
		$self = new $class();
		return $self->prepQuery($query, $values);
	}

	static function get($key=false, $value=false) {
		$class = get_called_class();
		$self = new $class();
		if ($key === false && $value === false) {
			return $self->buildQuery();
		}
		if (is_array($key) || is_object($key)) {
			if (is_array($key)) {
				$key = (object) $key;
			}
			return $self->buildQuery($key);
		}
		if ($value !== false) {
			return $self->buildQuery(array( $key => $value ));
		}
		$o = $self->buildQuery($key);
		if (!empty($o)) {
			return array_shift($o);
		}
		return array();
	}

	static function select($key=false, $value=false) {
		return self::get($key, $value);
	}

	static function delete($pk) {
		$class = get_called_class();
		$self = new $class();
		return $self->deleteRecord();
	}

	static function walk($tables, $search_obj=array()) {
		$class = get_called_class();
		$self = new $class();
		return $self->join($tables, $search_obj);
	}

	static function distinct($column, $search=array()) {
		$class = get_called_class();
		$self = new $class();
		$search['distinct'] = $column;
		return $self->buildQuery($search);
	}

	static function count($search=array()) {
		$class = get_called_class();
		$self = new $class();
		$result = $self->buildQuery($search, true);
		if (empty($result)) {
			return 0;
		}
		$result = array_shift($result);
		return $result->count;
	}
	
	static function select_with_children($user_id, $search=false) {
		if ($search && !is_array($search) && !is_object($search)) {
			error_log('select_with_children: you\'re using it wrong');
			return array();
		} else {
			$search = ($search) ? $search : array('user_id' => $user_id);
		}
		$search = (object) $search;
		$search->user_id = $user_id;
		$rows = self::get($search);
		$children = Users::get_accounts($user_id);
		foreach ($children as $child) {
			$search->user_id = $child->id;
			$child_rows = self::get($search);
			$rows = array_merge($rows, $child_rows);
		}
		return $rows;
	}
	
	private function _notify_slack($message) {
		return false;
		$data = json_encode(array(
			'channel' => '#logs',
			'username' => 'Log Bot',
			'text' => $message
		));
		//error_log($data);
		$ch = curl_init('https://hooks.slack.com/services/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');
		curl_setopt($ch, CURLOPT_PROXY, "172.30.0.5:9988");
		curl_setopt($ch, CURLOPT_PROXYPORT, "9988");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$resp = curl_exec($ch);
		curl_close($ch);
	}

	private function _send_hook($message) {
		$message = http_build_query($message);
		$ch = curl_init('http://208.103.144.152:8888/hook?token=92c8777c-db9a-49c0-a2fc-82a22a14a934');
		curl_setopt($ch, CURLOPT_PROXY, '172.30.0.5:9988');
		curl_setopt($ch, CURLOPT_PROXYPORT, '9988');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$resp = curl_exec($ch);
		curl_close($ch);
	}
}

