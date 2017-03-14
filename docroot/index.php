<?php

include('../lib/includes/configs.incl.php');
include(INCLUDES_PATH . 'autoloaders.incl.php');

session_start();

$f3 = require_once(LIB_PATH . 'f3/base.php');
$f3->set('DEBUG', 3);

//Login Controller
$f3->route('GET /login', 'LoginController->login_page');
$f3->route('POST /login', 'LoginController->authenticate'); //ajax
$f3->route('GET /logout', 'LoginController->login_page');
$f3->route('GET /signup', 'LoginController->signup_page');
$f3->route('POST /signup', 'LoginController->signup'); //ajax

//Index Controller
$f3->route('GET /', 'IndexController->landing_page');

//Numbers Controller
$f3->route('GET /numbers', 'NumbersController->numbers_page');
$f3->route('POST /numbers/list', 'NumbersController->list_numbers'); //ajax

//MA Controller
$f3->route('GET /ma/addresses', 'MAController->addresses_page');
$f3->route('POST /ma/addresses/create', 'MAController->create_group'); //ajax
$f3->route('POST /ma/addresses/list', 'MAController->list_groups'); //ajax
$f3->route('POST /ma/addresses/get', 'MAController->get_group'); //ajax
$f3->route('POST /ma/addresses/modify', 'MAController->modify_group'); //ajax
$f3->route('POST /ma/addresses/remove', 'MAController->remove_group'); //ajax

$f3->run();
