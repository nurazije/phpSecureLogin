<?php
require_once '../user.php';
require_once 'config.php';

$email = strip_tags($_POST['email']);
$code = $_POST['code'];

if($user->emailActivation($email,$code)){
	die;
}else{
	$user->printMsg();
	die;
}