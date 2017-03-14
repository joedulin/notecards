<?php

require_once(LIB_PATH . 'BaseMVC.class.php');
$files = array();

function list_path($dir, $prefix='') {
	$dir = rtrim($dir, '\\/');
	$result = array();

	foreach (scandir($dir) as $f) {
		if ($f !== '.' && $f !== '..') {
			if (is_dir("$dir/$f")) {
				$result = array_merge($result, list_path("$dir/$f", "$prefix$f/"));
			} else {
				$result[] = $prefix.$f;
			}
		}
	}
	return $result;
}

function autoload_framework_classes($class) {
	global $files;
	$classname = sprintf("%s.class.php", $class);
	$files = (empty($files)) ? list_path(LIB_PATH) : $files;
	foreach ($files as $file) {
		$cf = explode('/', $file);
		$cf = array_pop($cf);
		if ($classname == $cf) {
			require LIB_PATH . $file;
			return;
		}
	}
}

spl_autoload_register('autoload_framework_classes');

