<?php

class BaseController extends BaseMVC {
	public $f3;

	public function __construct($f3, $params=array(), $check_login=true) {
		parent::__construct();
		$this->f3 = $f3;
		$this->params = $params;
		if ($check_login) {
			if (!isset($_SESSION['user'])) {
				$f3->reroute('/login');
				return false;
			}
		}
	}

	public function pageRender($template, $usernav=true) {
		$page_content_file = TEMPLATE_PATH . $template . '.template.php';
		if (file_exists($page_content_file)) {
			include(TEMPLATE_PATH . 'global/page.template.php');
		} else {
			$this->f3->error(404);
		}
	}

	public function json($data) {
		header('Content-type: application/json');
		echo json_encode($data);
	}

	public function jsonSuccess($data=array()) {
		$resp = array(
			'status' => 0,
			'data' => $data
		);
		$this->json($resp);
	}

	public function jsonError($data=array()) {
		$resp = array(
			'status' => -1,
			'data' => $data
		);
		$this->json($resp);
	}

	public function debug($data, $var_dump=false) {
		echo '<pre>';
		if ($var_dump) {
			var_dump($data);
		} else {
			print_r($data);
		}
		exit;
	}
}
