<?php
require_once '../user.php';
require_once 'config.php';

$user->logout();

header('location: index.htm');

?>