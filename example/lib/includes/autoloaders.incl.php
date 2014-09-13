<?php

require_once(LIB_PATH . 'BaseMVC.class.php');

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
	$classname = sprintf("%s.class.php", $class);
	$files = list_path(LIB_PATH);
	foreach ($files as $file) {
		if (strpos($file, $classname) !== false) {
			include LIB_PATH . $file;
			return;
		}
	}
}

spl_autoload_register('autoload_framework_classes');
