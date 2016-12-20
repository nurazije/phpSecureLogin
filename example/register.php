<?php
require_once '../class/user.php';
require_once 'config.php';

$email = strip_tags($_POST['email']);
$fname = strip_tags($_POST['fname']);
$lname = strip_tags($_POST['lname']);
$pass = strip_tags($_POST['pass']);


if($user->registration($email,$fname,$lname,$pass)){
	print 'A confirmation mail has been sent, plase confirm your account registration!';
	die;
}else{
	$user->printMsg();
	die;
}