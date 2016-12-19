<?php
session_start();
define('conString', 'mysql:host=localhost;dbname=login');
define('dbUser', 'root');
define('dbPass', 'root');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user = new User();
$user->dbConnect(conString, dbUser, dbPass);