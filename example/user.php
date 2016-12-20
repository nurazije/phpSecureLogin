<?php
require_once '../class/user.php';
require_once 'config.php';
if($_SESSION['user']['id'] !== ''){
  $user->render('inc/userpage.php');
}else{
	header('Location: index.php');
}
?>
