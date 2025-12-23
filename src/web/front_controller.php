<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

require_once '../classes/App.php';

session_start();

$frontController = new App;
$frontController->run();
