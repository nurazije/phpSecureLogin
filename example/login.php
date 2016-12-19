<?php
require_once '../user.php';
require_once 'config.php';

$email = strip_tags($_POST['username']);
$password = $_POST['password'];

if($user->login($email,$password)){
	die;
}else{
	$user->printMsg();
	die;
}