<?php

class NumbersController extends BaseController {
	public function __construct($f3=array(), $params=array()) {
		parent::__construct($f3, $params);
	}

	public function numbers_page() {
		$this->pageheader = 'Numbers';
		$this->pagesubheader = 'My Numbers';
		$this->pageRender('numbers/my_numbers');
	}

	public function add_numbers_page() {
		$this->pageheader = 'Numbers';
		$this->pagesubheader = 'Add Numbers';
		$this->pageRender('numbers/add_numbers');
	}

	//ajax -----------------------------------------------------------------------
	public function list_numbers() {
		$limit = $this->get('limit', 100);
		$offset = $this->get('offset', 0);
		$search = $this->get('search', false);
		$limit = preg_replace('/[^0-9]/', '', $limit);
		$offset = preg_replace('/[^0-9]/', '', $offset);
		if ($search) {
			$query = sprintf("SELECT d.* WHERE user_id = ? AND number LIKE ? LIMIT %s OFFSET %s", $limit, $offset);
			$numbers = Dids::query($query, $this->user->id, sprintf('%%%s%%', $search));
		} else {
			$numbers = Dids::select(array(
				'user_id' => $this->user->id,
				'limit' => $limit,
				'offset' => $offset
			));
		}
		foreach ($numbers as $number) {
			$number->ma = MaGroups::select($number->group_id);
		}
		return $this->res(200, 'success', $numbers);
	}
}
