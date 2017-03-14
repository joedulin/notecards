<?php

class BaseController extends BaseMVC {
	public $domain;
	public $f3;
	public $token;
	public $user;
	public $security_items;
	public $api;
	public $vars;
	public $jsfiles;
	public $cssfiles;
	public $write;
	public $impersonate;

	public function __construct($f3, $params=array(), $check_login=true) {
		parent::__construct();
		if ($this->get('serverstats')) {
			$this->debug($_SERVER);
		}
		$this->f3 = $f3;
		$this->params = $params;
		$this->vars = (object) array();
		$this->jsfiles = array();
		$this->cssfiles = array();

		if ($check_login) {
			if (!isset($_SESSION['user'])) {
				$_SESSION['redirect'] = $this->f3->get('PATH');
				$this->f3->reroute('/login');
				exit;
			}
			$this->user = $_SESSION['user'];
		}
	}

	public function beforeRoute($f3, $params) {
		$this->f3 = $f3;
		$this->params = $params;
	}

	public function pageRender($template, $usernav=true, $string_render=false) {
		$this->write = true;
		$this->string_render = $string_render;
		if ($usernav) {
			$this->shownav = true;
		} else {
			$this->shownav = false;
		}
		$page_content_file = ($string_render) ? $template : TEMPLATE_PATH . $template . '.template.php';
		$this->pageheader = (isset($this->pageheader)) ? $this->pageheader : false;
		$this->pagesubheader = (isset($this->pagesubheader)) ? $this->pagesubheader : false;
		if ($string_render) {
			$vars = $this->vars;
			include(TEMPLATE_PATH . 'global/page.template.php');
		} else if (file_exists($page_content_file)) {
			$jscheck = sprintf('/js/%s.js', $template);
			$csscheck = sprintf('/css/%s.css', $template);
			if (!in_array($jscheck, $this->jsfiles)) {
				if (file_exists(DOCROOT . $jscheck)) {
					$this->jsfiles[] = $jscheck;
				}
			}
			if (!in_array($csscheck, $this->cssfiles)) {
				if (file_exists(DOCROOT . $csscheck)) {
					$this->cssfiles[] = $csscheck;
				}
			}
			$vars = $this->vars;
			include(TEMPLATE_PATH . 'global/page.template.php');
		} else {
			$this->f3->error(404);
		}
	}
	
	public function stringRender($string, $navbar=true) {
		$this->write = true;
		$this->string_render = true;
		if ($usernav) {
			$this->navmap = $this->get_nav_items();
			$write_requirements = array();
			$path = $this->f3->get('PATH');
			$found = false;
			foreach ($this->navmap as $side => $headers) {
				foreach ($headers as $header => $pages) {
					if (isset($pages['view'])) {
						if ($pages['view'] == $path) {
							$write_requirements = $pages['write'];
							$found = true;
							break;
						}
					} else {
						foreach ($pages as $pageheader => $page) {
							if ($page['view'] == $path) {
								$write_requirements = $page['write'];
								$found = true;
								break;
							}
						}
					}
				}
				if ($found) {
					break;
				}
			}
			foreach ($write_requirements as $item) {
				if (!in_array($item, array_keys((array) $this->security_items))) {
					$this->write = false;
				}
			}
		}
		$page_content_file = TEMPLATE_PATH . $template . '.template.php';
		$this->pageheader = (isset($this->pageheader)) ? $this->pageheader : false;
		$this->pagesubheader = (isset($this->pagesubheader)) ? $this->pagesubheader : false;
		if (file_exists($page_content_file)) {
			$jscheck = sprintf('/js/%s.js', $template);
			$csscheck = sprintf('/css/%s.css', $template);
			if (!in_array($jscheck, $this->jsfiles)) {
				if (file_exists(DOCROOT . $jscheck)) {
					$this->jsfiles[] = $jscheck;
				}
			}
			if (!in_array($csscheck, $this->cssfiles)) {
				if (file_exists(DOCROOT . $csscheck)) {
					$this->cssfiles[] = $csscheck;
				}
			}
			$vars = $this->vars;
			include(TEMPLATE_PATH . 'global/page.template.php');
		} else {
			$this->f3->error(404);
		}
	}

	public function json($data) {
		header('Content-type: application/json');
		echo json_encode($data);
	}

	public function res($code, $status, $data) {
		$this->json(array(
			'code' => $code,
			'status' => $status,
			'data' => $data
		));
	}

	public function get($name, $default=false) {
		if (isset($_REQUEST[$name])) {
			$this->$name = (is_array($_REQUEST[$name])) ? $_REQUEST[$name] : trim($_REQUEST[$name]);
			return $this->$name;
		}
		if (isset($this->params[$name])) {
			$this->$name = (is_array($this->params[$name])) ? $this->params[$name] : trim($this->params[$name]);
			return $this->$name;
		}
		$this->$name = $default;
		return $default;
	}

	public function required($name) {
		if (isset($_REQUEST[$name])) {
			$this->$name = (is_array($_REQUEST[$name])) ? $_REQUEST[$name] : trim($_REQUEST[$name]);
			return $this->$name;
		}
		if (isset($this->params[$name])) {
			$this->$name = (is_array($this->params[$name])) ? $this->params[$name] : trim($this->params[$name]);
			return $this->$name;
		}
		echo $name . ' is a required field';
		exit;
	}

	public function getvars($required=array(), $optional=array()) {
		foreach ($required as $r) {
			$this->required($r);
		}
		foreach ($optional as $n => $d) {
			$this->get($n, $d);
		}
	}

	public function get_nav_items($security_items=false) {
		$security_items = ($security_items) ? $security_items : $this->security_items;
		$full_map = include INCLUDES_PATH . 'navbar_map.incl.php';
		foreach ($full_map as $side => $map) {
			foreach ($map as $header => $data) {
				if (isset($data['read'])) {
					foreach ($data['read'] as $perm) {
						if (!in_array($perm, array_keys((array) $security_items))) {
							unset($full_map[$side][$header]);
							continue;
						}
					}
					continue;
				}
				foreach ($data as $page => $perms) {
					foreach ($perms['read'] as $perm) {
						if (!in_array($perm, array_keys((array) $security_items))) {
							unset($full_map[$side][$header][$page]);
							break;
						}
					}
				}
			}
		}
		foreach ($full_map as $side => $map) {
			foreach ($map as $header => $data) {
				if (empty($data)) {
					unset($full_map[$side][$header]);
					continue;
				}
			}
		}
		return $full_map;
	}

	public function check_security($item_name) {
		return (array_key_exists($item_name, (array) $this->security_items));
	}
}

