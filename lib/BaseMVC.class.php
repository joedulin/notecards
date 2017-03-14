<?php

class BaseMVC {
	public $configs;

	public function __construct() {
		$this->_setConfigs();
	}

	private function _setConfigs() {
		//Set global configs here
	}

	public function debug($var, $dump=false) {
		if ($dump) {
			var_dump($var);
			exit;
		}
		printf('<pre>%s</pre>', print_r($var, true));
		exit;
	}

	public function uuid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,

			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}

	public function states_array() {
		return array(
			'AL'=>'Alabama',
			'AK'=>'Alaska',
			'AZ'=>'Arizona',
			'AR'=>'Arkansas',
			'CA'=>'California',
			'CO'=>'Colorado',
			'CT'=>'Connecticut',
			'DE'=>'Delaware',
			'DC'=>'District of Columbia',
			'FL'=>'Florida',
			'GA'=>'Georgia',
			'HI'=>'Hawaii',
			'ID'=>'Idaho',
			'IL'=>'Illinois',
			'IN'=>'Indiana',
			'IA'=>'Iowa',
			'KS'=>'Kansas',
			'KY'=>'Kentucky',
			'LA'=>'Louisiana',
			'ME'=>'Maine',
			'MD'=>'Maryland',
			'MA'=>'Massachusetts',
			'MI'=>'Michigan',
			'MN'=>'Minnesota',
			'MS'=>'Mississippi',
			'MO'=>'Missouri',
			'MT'=>'Montana',
			'NE'=>'Nebraska',
			'NV'=>'Nevada',
			'NH'=>'New Hampshire',
			'NJ'=>'New Jersey',
			'NM'=>'New Mexico',
			'NY'=>'New York',
			'NC'=>'North Carolina',
			'ND'=>'North Dakota',
			'OH'=>'Ohio',
			'OK'=>'Oklahoma',
			'OR'=>'Oregon',
			'PA'=>'Pennsylvania',
			'RI'=>'Rhode Island',
			'SC'=>'South Carolina',
			'SD'=>'South Dakota',
			'TN'=>'Tennessee',
			'TX'=>'Texas',
			'UT'=>'Utah',
			'VT'=>'Vermont',
			'VA'=>'Virginia',
			'WA'=>'Washington',
			'WV'=>'West Virginia',
			'WI'=>'Wisconsin',
			'WY'=>'Wyoming',
			'AB'=>'Alberta',
			'BC'=>'British Columbia',
			'LB'=>'Labrador',
			'MB'=>'Manitoba',
			'NB'=>'New Brunswick',
			'NF'=>'Newfoundland',
			'NT'=>'Northwest Territories',
			'NS'=>'Nova Scotia',
			'VU'=>'Nunavut',
			'ON'=>'Ontario',
			'PE'=>'Prince Edward Island',
			'PQ'=>'Quebec',
			'SK'=>'Saskatchewan',
			'YT'=>'Yukon',
			'PR'=>'Puerto Rico'
		);
	}

	public function parseNum($number, $assoc=false) {
		$orignum = $number;

		$number = preg_replace('/[^0-9]/', '', $number);
		$number = strrev($number);
		$xxxx = strrev(substr($number, 0, 4));
		$nxx = strrev(substr($number, 4, 3));
		$npa = strrev(substr($number, 7, 3));
		$extra = strrev(substr($number, 10));
		$display = (strlen($extra) > 0) ? sprintf('+%s (%s) %s-%s', $extra, $npa, $nxx, $xxxx) : sprintf('(%s) %s-%s', $npa, $nxx, $xxxx);


		if (!$extra) {
			$extra = '1';
		}

		if ($assoc) {
			return array(
				'npa' => $npa,
				'nxx' => $nxx,
				'xxxx' => $xxxx,
				'country_code' => $extra,
				'number' => strrev($number),
				'display' => $display,
				'tendigit' => sprintf('%s%s%s', $npa, $nxx, $xxxx)
			);
		} else {
			return (object) array(
				'npa' => $npa,
				'nxx' => $nxx,
				'xxxx' => $xxxx,
				'extra' => $extra,
				'number' => strrev($number),
				'display' => $display,
				'tendigit' => sprintf('%s%s%s', $npa, $nxx, $xxxx)
			);
		}
	}

	public function roundTo($n, $x=5) {
		return (ceil($n)%$x === 0) ? ceil($n) : round(($n+$x/2)/$x)*$x;
	}

	public function dateFormat($mysql_date, $time=false) {
		$date = strtotime($mysql_date);
		//return ($time) ? date('d/m/Y H:i:s', $date) : date('d/m/Y');
		return ($time) ? date('m/d/Y H:i:s', $date) : date('m/d/Y', $date);
	}

	public function reIndex($array, $key) {
		$new = array();
		foreach ($array as $value) {
			$lookupkey = (is_object($value)) ? $value->$key : $value[$key];
			$new[$lookupkey] = $value;
		}
		return $new;
	}
	
	public function tollfree_array() {
		return array('800', '844', '855', '866', '877', '888');
	}

	public function hash_pass($password) {
		return hash('sha512', sprintf('this is a saltine%s', $password));
	}
}

