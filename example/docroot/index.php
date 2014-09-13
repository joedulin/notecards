<?php

include('../lib/includes/configs.incl.php');
include(INCLUDES_PATH . 'autoloaders.incl.php');

session_start();

$f3 = require_once(LIB_PATH . 'f3/base.php');

//Index controller
$f3->route('GET /', 'Index->landing');

//Login Controller
$f3->route('GET /login', 'Login->login');
$f3->route('POST /login', 'Login->loginAjax');
$f3->route('GET /logout', 'Login->logout');

//Account controller
$f3->route('GET /account/changepass', 'Account->change_password');
$f3->route('POST /account/changepass', 'Account->change_password_ajax');

//Test Controller
$f3->route('GET /test/session', 'Test->session');

$f3->run();
