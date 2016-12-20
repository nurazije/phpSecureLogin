<?php
	require_once '../class/user.php';
	require_once 'config.php';
	$user->render('inc/indexhead.htm');
	$user->render('inc/indextop.htm');
	$user->render('inc/loginform.php');
	$user->render('inc/activationform.php');
	$user->render('inc/indexmiddle.htm');
	$user->render('inc/registerform.php');
	$user->render('inc/indexfooter.htm');
?>