<?php

class MemcacheConnect extends BaseConnect {
	private $_memcache;



	public function __construct() {

		parent::__construct();

		$this->_memcache = new Memcache();

		$this->_memcache->connect('localhost', '11211');

	}



	/**

	 * Fetches a memcached value based on key

	 *

	 * @access public

	 *

	 * @param string $key the hash key

	 * @return string|boolean serialized string if found, boolean false if not

	 */

	function get($key = false) {

		if ($key) {

			$val = $this->_memcache->get($key);

			if ($val) {

				return $val;

			}

		}

		return false;

	}



	/**

	 * Sets a memcached value based on key

	 *

	 * @access public

	 *

	 * @param string $key the hash key

	 * @param string $val the serialized value

	 * @param integer optional timeout Timeout in seconds

	 * @return boolean (true/false)

	 */

	public function set($key = false, $val = false, $timeout = 3600) {

		if ($key && $val) {

			// $timeout defaults to 1 hour

			$ret = $this->_memcache->set($key, $val, MEMCACHE_COMPRESSED, $timeout);

			if ($ret) {

				return true;

			}

		}

		return false;

	}



	/**

	 * Deletes a memcached value based on key

	 *

	 * @access public

	 *

	 * @param string $key the hash key

	 * @return boolean (true/false)

	 */

	public function delete($key) {

		if ($key) {

			return $this->_memcache->delete($key);

		}

		return false;

	}



	/**

	 * Wrapper to package a memcached request -- adds serialization

	 *

	 * @access public

	 *

	 * @param string $key the hash key

	 * @param mixed $val the value to cache

	 * @param integer optional timeout Timeout in seconds

	 * @return boolean (true/false)

	 */

	public function wrap($key = false, $val = false, $timeout = 3600) {

		if ($key && $val) {
			$md5_key = $this->makekey($key);
			$ser_val = serialize($val);

			return $this->set($md5_key, $ser_val, $timeout);

		}

		return false;

	}



	/**

	 * Wrapper to un-package a memcached request -- strips serialization

	 *

	 * @access public

	 *

	 * @param string $key the hash key

	 * @return mixed|boolean object if found, boolean false if not

	 */

	public function unwrap($key = false) {

		if ($key) {
			$key = $this->makekey($key);
			$val = $this->get($key);

			if ($val) {

				return unserialize($val);

			}

		}

		return false;

	}



	public function makekey($text) {

		return md5($text);

	}
}
