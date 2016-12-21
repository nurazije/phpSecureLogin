<?php
	require_once '../class/user.php';
	require_once 'config.php';
	if($_SESSION['user']['id'] !== ''){
	  	$user->userPage();
	}else{
		header('Location: index.php');
	}
?>
