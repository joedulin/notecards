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
$f3->route('GET /forgot', 'LoginController->forgotpass_page');

//Index Controller
$f3->route('GET /', 'IndexController->landing_page');

//Projects Controller
$f3->route('POST /projects/create', 'ProjectsController->create_project'); //ajax
$f3->route('POST /projects/list', 'ProjectsController->list_projects'); //ajax
$f3->route('POST /projects/remove', 'ProjectsController->remove_project'); //ajax

//Card Controller
$f3->route('POST /cards/create', 'CardsController->create_card'); //ajax
$f3->route('POST /cards/list', 'CardsController->list_cards'); //ajax
$f3->route('POST /cards/search', 'CardsController->search_cards'); //ajax
$f3->route('POST /cards/update', 'CardsController->update_card'); //ajax
$f3->route('POST /cards/remove', 'CardsController->remove_card'); //ajax

//Test Controller
$f3->route('GET|POST /test/session', 'TestController->session');
$f3->route('GET|POST /test/this', 'TestController->this');

$f3->run();
