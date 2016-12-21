<?php
    require_once '../class/user.php';
    require_once 'config.php';

    $email = Isset($_POST['username']) ? $_POST['username'] : '';
    $password = Isset($_POST['password']) ? $_POST['password'] : '';
    if( $user->login( $email, $password) ) {
        die;
    } else {
        $user->printMsg();
        die;
    }
